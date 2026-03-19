<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customer = \App\Models\User::whereHas('role', function ($q) {
            $q->where('slug', 'customer');
        })->first();

        if ($customer) {
            \App\Models\Notification::create([
                'user_id' => $customer->id,
                'message' => 'Your booking has been approved!',
                'type' => 'booking_confirmation',
                'read_status' => false,
            ]);
        }
    }
}
