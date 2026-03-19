<?php

namespace App\Livewire\Vehicles;

use App\Models\Vehicle;
use App\Models\Booking;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class VehicleList extends Component
{
    use WithPagination;

    public $search = '';
    public $type = '';
    public $brand = '';
    public $min_price = '';
    public $max_price = '';
    public $availability = '';
    public $filter_start_date = '';
    public $filter_end_date = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingType()
    {
        $this->resetPage();
    }
    public function updatingBrand()
    {
        $this->resetPage();
    }
    public function updatingMinPrice()
    {
        $this->resetPage();
    }
    public function updatingMaxPrice()
    {
        $this->resetPage();
    }
    public function updatingAvailability()
    {
        $this->resetPage();
    }

    public function updatingFilterStartDate()
    {
        $this->resetPage();
    }

    public function updatingFilterEndDate()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Vehicle::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('make', 'like', '%' . $this->search . '%')
                    ->orWhere('model', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->type) {
            $query->where('type', $this->type);
        }

        if ($this->brand) {
            $query->where('make', $this->brand);
        }

        if ($this->min_price) {
            $query->where('price', '>=', $this->min_price);
        }

        if ($this->max_price) {
            $query->where('price', '<=', $this->max_price);
        }

        
        $todayStart = Carbon::today()->startOfDay();
        $todayEnd = Carbon::today()->endOfDay();

        $checkStart = $this->filter_start_date ? Carbon::parse($this->filter_start_date)->startOfDay() : $todayStart;
        $checkEnd = $this->filter_end_date ? Carbon::parse($this->filter_end_date)->endOfDay() : $todayEnd;

        if ($this->availability) {
            if ($this->availability === 'available') {
                
                $query->where('availability_status', 'available')
                    ->whereDoesntHave('bookings', function ($q) use ($checkStart, $checkEnd) {
                        $q->whereIn('status', [Booking::STATUS_APPROVED, Booking::STATUS_PENDING])
                            ->where('start_date', '<=', $checkEnd)
                            ->where('end_date', '>=', $checkStart);
                    });
            } elseif ($this->availability === 'booked') {
                
                $query->whereHas('bookings', function ($q) use ($checkStart, $checkEnd) {
                    $q->whereIn('status', [Booking::STATUS_APPROVED, Booking::STATUS_PENDING])
                        ->where('start_date', '<=', $checkEnd)
                        ->where('end_date', '>=', $checkStart);
                });
            } else {
                
                $query->where('availability_status', $this->availability);
            }
        }

        
        $query->withExists(['bookings as is_booked' => function ($q) use ($checkStart, $checkEnd) {
            $q->whereIn('status', [Booking::STATUS_APPROVED, Booking::STATUS_PENDING])
                ->where('start_date', '<=', $checkEnd)
                ->where('end_date', '>=', $checkStart);
        }]);

        $vehicles = $query->paginate(9);

       
        foreach ($vehicles as $vehicle) {
            $vehicle->available_from = null;
            if (!empty($vehicle->is_booked)) {
                $nextAvailableBooking = $vehicle->bookings()
                    ->whereIn('status', [Booking::STATUS_APPROVED, Booking::STATUS_PENDING])
                    ->where('end_date', '>=', $checkStart)
                    ->orderBy('end_date', 'asc')
                    ->first();

                if ($nextAvailableBooking) {
                    $vehicle->available_from = Carbon::parse($nextAvailableBooking->end_date)->addDay()->toDateString();
                }
            }
        }

        $types = Vehicle::distinct()->pluck('type');
        $brands = Vehicle::distinct()->pluck('make');

        
        return view('livewire.vehicles.vehicle-list', [
            'vehicles' => $vehicles,
            'types' => $types,
            'brands' => $brands,
        ]);
    }
}
