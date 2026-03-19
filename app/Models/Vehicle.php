<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'make', 'model', 'year', 'type', 'price', 'availability_status', 'image_path',
        'license_plate_number', 'registration_number', 'fuel_type', 'transmission_type',
        'mileage', 'seating_capacity', 'engine_or_chassis_number', 'has_air_conditioning',
        'has_bluetooth', 'description'
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function images()
    {
        return $this->hasMany(VehicleImage::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(VehicleImage::class)->where('is_primary', true);
    }
}
