<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Vehicles\VehicleList;
use App\Livewire\Vehicles\VehicleManager;
use App\Livewire\Bookings\BookingManager;
use App\Livewire\Bookings\CreateBooking;
use App\Livewire\Notifications\NotificationList;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\ReportManager;

Route::get('/', \App\Livewire\Vehicles\VehicleList::class)->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Vehicle Routes
Route::get('/vehicles', \App\Livewire\Vehicles\VehicleList::class)->name('vehicles.index');

Route::middleware(['auth'])->group(function () {
    // Booking Routes
    Route::get('/bookings', \App\Livewire\Bookings\BookingManager::class)->name('bookings.index');
    Route::get('/bookings/create/{vehicle_id}', \App\Livewire\Bookings\CreateBooking::class)->name('bookings.create');
    Route::get('/admin/vehicles', \App\Livewire\Vehicles\VehicleManager::class)->name('admin.vehicles');

    // Notifications
    Route::get('/notifications', \App\Livewire\Notifications\NotificationList::class)->name('notifications.index');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', \App\Livewire\Admin\Dashboard::class)->name('admin.dashboard');
    // Route::get('/admin/vehicles', \App\Livewire\Vehicles\VehicleManager::class)->name('admin.vehicles');
    Route::get('/admin/reports', \App\Livewire\Admin\ReportManager::class)->name('admin.reports');
});

// Manager Routes
Route::middleware(['auth', 'role:manager'])->group(function () {
   
});

require __DIR__ . '/auth.php';
