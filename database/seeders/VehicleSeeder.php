<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicles = [
            ['make' => 'Toyota', 'model' => 'Camry', 'year' => 2023, 'type' => 'Sedan', 'price' => 50.00, 'availability_status' => 'available'],
            ['make' => 'Honda', 'model' => 'Civic', 'year' => 2022, 'type' => 'Sedan', 'price' => 45.00, 'availability_status' => 'available'],
            ['make' => 'Ford', 'model' => 'Mustang', 'year' => 2024, 'type' => 'Sports', 'price' => 120.00, 'availability_status' => 'available'],
            ['make' => 'Tesla', 'model' => 'Model 3', 'year' => 2023, 'type' => 'Electric', 'price' => 100.00, 'availability_status' => 'available'],
            ['make' => 'BMW', 'model' => 'X5', 'year' => 2023, 'type' => 'SUV', 'price' => 150.00, 'availability_status' => 'available'],
            ['make' => 'Audi', 'model' => 'A4', 'year' => 2022, 'type' => 'Luxury', 'price' => 80.00, 'availability_status' => 'available'],
            ['make' => 'Mercedes', 'model' => 'C-Class', 'year' => 2023, 'type' => 'Luxury', 'price' => 90.00, 'availability_status' => 'available'],
            ['make' => 'Hyundai', 'model' => 'Elantra', 'year' => 2022, 'type' => 'Sedan', 'price' => 40.00, 'availability_status' => 'available'],
            ['make' => 'Kia', 'model' => 'Sportage', 'year' => 2023, 'type' => 'SUV', 'price' => 60.00, 'availability_status' => 'available'],
            ['make' => 'Chevrolet', 'model' => 'Tahoe', 'year' => 2024, 'type' => 'SUV', 'price' => 180.00, 'availability_status' => 'available'],
        ];

        foreach ($vehicles as $vehicle) {
            \App\Models\Vehicle::create($vehicle);
        }
    }
}
