<?php

namespace App\Livewire\Admin;

use App\Models\Vehicle;
use App\Models\Booking;
use App\Models\User;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $totalVehicles = Vehicle::count();
        $totalBookings = Booking::count();
        $totalCustomers = User::whereHas('role', function ($q) {
            $q->where('slug', 'customer');
        })->count();

        $revenue = Booking::where('status', 'approved')->with('vehicle')->get()->sum(function ($booking) {
            $days = \Carbon\Carbon::parse($booking->start_date)->diffInDays(\Carbon\Carbon::parse($booking->end_date));
            if ($days == 0)
                $days = 1;
            return $days * $booking->vehicle->price;
        });

        $recentBookings = Booking::with(['user', 'vehicle'])->latest()->take(5)->get();

        return view('livewire.admin.dashboard', [
            'totalVehicles' => $totalVehicles,
            'totalBookings' => $totalBookings,
            'totalCustomers' => $totalCustomers,
            'totalRevenue' => $revenue,
            'recentBookings' => $recentBookings,
        ]);
    }
}