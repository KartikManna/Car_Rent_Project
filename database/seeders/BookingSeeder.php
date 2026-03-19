<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customer = \App\Models\User::whereHas('role', function ($q) {
            $q->where('slug', 'customer');
        })->first();

        $vehicle = \App\Models\Vehicle::first();

        if ($customer && $vehicle) {
            \App\Models\Booking::create([
                'user_id' => $customer->id,
                'vehicle_id' => $vehicle->id,
                'start_date' => now()->addDays(1),
                'end_date' => now()->addDays(3),
                'status' => 'approved',
                'payment_status' => 'paid',
            ]);
        }
    }
}
