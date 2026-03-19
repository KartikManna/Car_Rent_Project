<?php

namespace App\Livewire\Bookings;

use App\Models\Booking;
use App\Models\Notification;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateBooking extends Component
{
    public $vehicle_id;

    public $vehicle;

    public $start_date;

    public $end_date;

    public $total_price = 0;

    public function mount($vehicle_id)
    {
        $this->vehicle_id = $vehicle_id;
        $this->vehicle = Vehicle::findOrFail($vehicle_id);
    }

    public function updated($propertyName)
    {
        if ($propertyName == 'start_date' || $propertyName == 'end_date') {
            $this->calculateTotalPrice();
        }
    }

    public function calculateTotalPrice()
    {
        if ($this->start_date && $this->end_date) {
            try {
                $start = Carbon::parse($this->start_date);
                $end = Carbon::parse($this->end_date);
                $days = $start->diffInDays($end);
                if ($days < 0) {
                    $this->total_price = 0;
                } else {
                    if ($days == 0) {
                        $days = 1;
                    }
                    $this->total_price = $days * $this->vehicle->price;
                }
            } catch (\Exception $e) {
                $this->total_price = 0;
            }
        }
    }

    public function book()
    {

        $this->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
        ]);

        try {

            $start = Carbon::parse($this->start_date)->startOfDay();
            $end = Carbon::parse($this->end_date)->endOfDay();
        } catch (\Exception $e) {
            session()->flash('error', 'Invalid dates.');

            return;
        }

        $days = $start->diffInDays($end) + 1;

        if ($days > 90) {
            session()->flash('error', 'Maximum 90 days allowed.');

            return;
        }

        try {
            DB::transaction(function () use ($start, $end, $days) {

                $vehicle = Vehicle::where('id', $this->vehicle_id)
                    ->lockForUpdate()
                    ->first();

                $isBooked = Booking::where('vehicle_id', $this->vehicle_id)
                    ->whereIn('status', [
                        Booking::STATUS_PENDING,
                        Booking::STATUS_APPROVED,
                    ])
                    ->where(function ($query) use ($start, $end) {
                        $query->where('start_date', '<=', $end)
                            ->where('end_date', '>=', $start);
                    })
                    ->lockForUpdate()
                    ->exists();

                if ($isBooked) {
                    throw new \Exception('overlap');
                }

                $userBooked = Booking::where('user_id', auth()->id())
                    ->where('vehicle_id', $this->vehicle_id)
                    ->whereIn('status', [
                        Booking::STATUS_PENDING,
                        Booking::STATUS_APPROVED,
                    ])
                    ->where(function ($query) use ($start, $end) {
                        $query->where('start_date', '<=', $end)
                            ->where('end_date', '>=', $start);
                    })
                    ->exists();

                if ($userBooked) {
                    throw new \Exception('duplicate');
                }

                $totalPrice = $days * $vehicle->price;

                Booking::create([
                    'user_id' => auth()->id(),
                    'vehicle_id' => $this->vehicle_id,
                    'start_date' => $start,
                    'end_date' => $end,
                    'total_price' => $totalPrice,
                    'status' => Booking::STATUS_PENDING,
                    'payment_status' => Booking::PAYMENT_UNPAID,
                ]);
            });

            Notification::create([
                'user_id' => auth()->id(),
                'message' => 'Booking request submitted successfully.',
                'type' => 'booking_request',
            ]);

            session()->flash('message', 'Booking successful!');

            return $this->redirect(route('bookings.index'), navigate: true);

        } catch (\Exception $e) {

            if ($e->getMessage() === 'overlap') {
                session()->flash('error', 'Vehicle already booked for selected dates.');

                return;
            }

            if ($e->getMessage() === 'duplicate') {
                session()->flash('error', 'You already booked this vehicle for these dates.');

                return;
            }

            \Log::error('Booking error: '.$e->getMessage());

            session()->flash('error', 'Something went wrong.');
        }
    }

    public function render()
    {
        return view('livewire.bookings.create-booking');
    }
}
