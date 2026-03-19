<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);

        $adminRole = \App\Models\Role::where('slug', 'admin')->first();
        $managerRole = \App\Models\Role::where('slug', 'manager')->first();
        $customerRole = \App\Models\Role::where('slug', 'customer')->first();

        // 1 Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@rental.com',
            'password' => bcrypt('password'),
            'role_id' => $adminRole->id,
        ]);

        // 1 Manager
        User::create([
            'name' => 'Manager User',
            'email' => 'manager@rental.com',
            'password' => bcrypt('password'),
            'role_id' => $managerRole->id,
        ]);

        // 3 Customers
        for ($i = 1; $i <= 3; $i++) {
            User::create([
                'name' => "Customer User $i",
                'email' => "customer$i@rental.com",
                'password' => bcrypt('password'),
                'role_id' => $customerRole->id,
            ]);
        }

        $this->call(VehicleSeeder::class);
        $this->call(BookingSeeder::class);
        $this->call(NotificationSeeder::class);
    }
}
