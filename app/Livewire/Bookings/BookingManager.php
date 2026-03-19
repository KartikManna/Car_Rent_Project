<?php

namespace App\Livewire\Bookings;

use App\Models\Booking;
use App\Models\Notification;
use Livewire\Component;
use Livewire\WithPagination;

class BookingManager extends Component
{
    use WithPagination;

    public function approve($id)
    {
        $booking = Booking::findOrFail($id);

        
        $conflict = Booking::where('vehicle_id', $booking->vehicle_id)
            ->where('id', '<>', $booking->id)
            ->where('status', Booking::STATUS_APPROVED)
            ->where(function ($q) use ($booking) {
                $q->whereBetween('start_date', [$booking->start_date, $booking->end_date])
                  ->orWhereBetween('end_date', [$booking->start_date, $booking->end_date])
                  ->orWhere(function ($q2) use ($booking) {
                      $q2->where('start_date', '<=', $booking->start_date)
                         ->where('end_date', '>=', $booking->end_date);
                  });
            })->exists();

        if ($conflict) {
            session()->flash('error', 'Cannot approve booking — it conflicts with an existing approved booking.');
            return;
        }

        $booking->update(['status' => Booking::STATUS_APPROVED]);

        Notification::create([
            'user_id' => $booking->user_id,
            'message' => "Your booking for {$booking->vehicle->make} {$booking->vehicle->model} has been approved.",
            'type' => 'booking_status',
        ]);

        session()->flash('message', 'Booking approved.');
    }

    public function reject($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'rejected']);

        Notification::create([
            'user_id' => $booking->user_id,
            'message' => "Your booking for {$booking->vehicle->make} {$booking->vehicle->model} has been rejected.",
            'type' => 'booking_status',
        ]);

        session()->flash('message', 'Booking rejected.');
    }

    public function cancel($id)
    {
        $booking = Booking::findOrFail($id);

       
        if ($booking->status === Booking::STATUS_APPROVED) {
            session()->flash('error', 'Approved bookings cannot be cancelled.');
            return;
        }

        $user = auth()->user();
        
        if ($user->isCustomer() && $booking->user_id !== $user->id) {
            session()->flash('error', 'You are not authorized to cancel this booking.');
            return;
        }

        $booking->update(['status' => Booking::STATUS_CANCELLED]);

        Notification::create([
            'user_id' => $booking->user_id,
            'message' => "Your booking for {$booking->vehicle->make} {$booking->vehicle->model} has been cancelled.",
            'type' => 'booking_status',
        ]);

        session()->flash('message', 'Booking cancelled.');
    }

    public function render()
    {
        $user = auth()->user();
        $query = Booking::with(['user', 'vehicle']);

        if ($user->isCustomer()) {
            $query->where('user_id', $user->id);
        }

        return view('livewire.bookings.booking-manager', [
            'bookings' => $query->latest()->paginate(10)
        ]);
    }
}
