<?php

namespace App\Livewire\Vehicles;

use App\Models\Vehicle;
use App\Models\VehicleImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class VehicleManager extends Component
{
    use WithFileUploads, WithPagination;

    public $vehicleId;
    public $vehicle;
    public $make;
    public $model;
    public $year;
    public $type;
    public $price;
    public $availability_status;
    public $images = [];
    public $existingImages = [];
    public $primaryNewIndex = null;
    public $license_plate_number;
    public $registration_number;
    public $fuel_type;
    public $transmission_type;
    public $mileage;
    public $seating_capacity;
    public $engine_or_chassis_number;
    public $has_air_conditioning = false;
    public $has_bluetooth = false;
    public $description;
    public $isOpen = false;

    protected $rules = [
        'make' => 'required|string|max:255',
        'model' => 'required|string|max:255',
        'year' => 'required|integer|min:1900|max:2099',
        'type' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'availability_status' => 'required|in:available,rented,maintenance',
        'license_plate_number' => 'nullable|string|max:50',
        'registration_number' => 'nullable|string|max:100',
        'fuel_type' => 'nullable|in:petrol,diesel,electric,hybrid',
        'transmission_type' => 'nullable|in:manual,automatic',
        'mileage' => 'nullable|string|max:100',
        'seating_capacity' => 'nullable|integer|min:1',
        'engine_or_chassis_number' => 'nullable|string|max:100',
        'has_air_conditioning' => 'boolean',
        'has_bluetooth' => 'boolean',
        'description' => 'nullable|string|max:2000',
        'images' => 'nullable|array',
        'images.*' => 'image|max:2048',
    ];

    protected $messages = [
        'make.required' => 'Please enter the vehicle make.',
        'model.required' => 'Please enter the vehicle model.',
        'price.required' => 'Please enter the price per day.',
        'year.required' => 'Please enter a valid year.',
        'availability_status.in' => 'Selected status is invalid.',
        'fuel_type.in' => 'Selected fuel type is invalid.',
        'transmission_type.in' => 'Selected transmission is invalid.',
        'seating_capacity.min' => 'Seating capacity must be at least 1.',
        'images.*.image' => 'Each uploaded file must be an image.',
        'images.*.max' => 'Each image must not exceed 2MB.',
    ];

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function edit($id)
    {
        $vehicle = Vehicle::with('images')->findOrFail($id);
        $this->vehicleId = $id;
        $this->vehicle = $vehicle;
        $this->make = $vehicle->make;
        $this->model = $vehicle->model;
        $this->year = $vehicle->year;
        $this->type = $vehicle->type;
        $this->price = $vehicle->price;
        $this->availability_status = $vehicle->availability_status;
        $this->license_plate_number = $vehicle->license_plate_number;
        $this->registration_number = $vehicle->registration_number;
        $this->fuel_type = $vehicle->fuel_type;
        $this->transmission_type = $vehicle->transmission_type;
        $this->mileage = $vehicle->mileage;
        $this->seating_capacity = $vehicle->seating_capacity;
        $this->engine_or_chassis_number = $vehicle->engine_or_chassis_number;
        $this->has_air_conditioning = (bool) $vehicle->has_air_conditioning;
        $this->has_bluetooth = (bool) $vehicle->has_bluetooth;
        $this->description = $vehicle->description;
        $this->images = [];
        $this->primaryNewIndex = null;
        $this->existingImages = $vehicle->images->map(function($i){
            return [
                'id' => $i->id,
                'path' => $i->path,
                'is_primary' => (bool) $i->is_primary,
            ];
        })->toArray();
        $this->openModal();
    }

    public function save()
    {
        $this->validate();

        $data = [
            'make' => $this->make,
            'model' => $this->model,
            'year' => $this->year,
            'type' => $this->type,
            'price' => $this->price,
            'availability_status' => $this->availability_status,
            'license_plate_number' => $this->license_plate_number,
            'registration_number' => $this->registration_number,
            'fuel_type' => $this->fuel_type,
            'transmission_type' => $this->transmission_type,
            'mileage' => $this->mileage,
            'seating_capacity' => $this->seating_capacity,
            'engine_or_chassis_number' => $this->engine_or_chassis_number,
            'has_air_conditioning' => $this->has_air_conditioning,
            'has_bluetooth' => $this->has_bluetooth,
            'description' => $this->description,
        ];

        $vehicle = Vehicle::updateOrCreate(['id' => $this->vehicleId], $data);

        
        if (!empty($this->images)) {
           
            if ($this->primaryNewIndex !== null) {
                VehicleImage::where('vehicle_id', $vehicle->id)->update(['is_primary' => false]);
            }
            $i = 0;
            foreach ($this->images as $img) {
                try {
                    $path = $img->store('vehicle', 'public');
                    $created = VehicleImage::create([
                        'vehicle_id' => $vehicle->id,
                        'path' => $path,
                    ]);

                    
                    if ($this->primaryNewIndex !== null && $i === (int) $this->primaryNewIndex) {
                        $created->is_primary = true;
                        $created->save();
                        $vehicle->image_path = $path;
                        $vehicle->save();
                    } else {
                        
                        if (! $vehicle->images()->where('is_primary', true)->exists()) {
                            $created->is_primary = true;
                            $created->save();
                            $vehicle->image_path = $path;
                            $vehicle->save();
                        }
                    }
                    $i++;
                } catch (\Exception $e) {
                    Log::error('Vehicle image store failed: ' . $e->getMessage());
                    session()->flash('error', 'One or more images failed to upload.');
                }
            }
        }

        
        $this->primaryNewIndex = null;

       
        if ($this->vehicleId) {
            $this->existingImages = Vehicle::find($vehicle->id)->images()->get()->map(function($i){
                return ['id'=>$i->id,'path'=>$i->path,'is_primary'=>(bool)$i->is_primary];
            })->toArray();
        }

        session()->flash('message', $this->vehicleId ? 'Vehicle updated.' : 'Vehicle created.');

        $this->resetInputFields();
        $this->closeModal();
    }

    public function delete($id)
    {
        $vehicle = Vehicle::with('images')->find($id);
        if ($vehicle) {
           
            foreach ($vehicle->images as $img) {
                try {
                    if (Storage::disk('public')->exists($img->path)) {
                        Storage::disk('public')->delete($img->path);
                    }
                } catch (\Exception $e) {
                   
                }
                $img->delete();
            }
            
            if ($vehicle->image_path) {
                try {
                    if (Storage::disk('public')->exists($vehicle->image_path)) {
                        Storage::disk('public')->delete($vehicle->image_path);
                    }
                } catch (\Exception $e) {
                  
                }
            }
            $vehicle->delete();
        }

        session()->flash('message', 'Vehicle deleted.');
    }

    public function deleteImage($id)
    {
        $img = VehicleImage::find($id);
        if (! $img) {
            return;
        }

        $vehicleId = $img->vehicle_id;
        $wasPrimary = (bool) $img->is_primary;
        try {
            Storage::disk('public')->delete($img->path);
        } catch (\Exception $e) {
            
        }

        $img->delete();

       
        if ($wasPrimary) {
            $next = VehicleImage::where('vehicle_id', $vehicleId)->first();
            $vehicle = Vehicle::find($vehicleId);
            if ($next) {
                $next->is_primary = true;
                $next->save();
                if ($vehicle) {
                    $vehicle->image_path = $next->path;
                    $vehicle->save();
                }
            } else {
                if ($vehicle) {
                    $vehicle->image_path = null;
                    $vehicle->save();
                }
            }
        }

        
        if ($this->vehicleId && $this->vehicleId == $vehicleId) {
            $this->existingImages = Vehicle::find($vehicleId)->images()->get()->map(function($i){
                return ['id'=>$i->id,'path'=>$i->path,'is_primary'=>(bool)$i->is_primary];
            })->toArray();
            
            if ($this->primaryNewIndex !== null) {
                
                if (! isset($this->images[$this->primaryNewIndex])) {
                    $this->primaryNewIndex = null;
                }
            }
        }

        session()->flash('message', 'Image deleted.');
        $this->dispatch('notify', ['message' => 'Image deleted.']);
    }

    public function setPrimaryImage($id)
    {
        $img = VehicleImage::find($id);
        if (! $img) {
            return;
        }

        
        VehicleImage::where('vehicle_id', $img->vehicle_id)->update(['is_primary' => false]);

        $img->is_primary = true;
        $img->save();

       
        $vehicle = Vehicle::find($img->vehicle_id);
        if ($vehicle) {
            $vehicle->image_path = $img->path;
            $vehicle->save();
            
            if ($this->vehicleId && $this->vehicleId == $vehicle->id) {
                $this->existingImages = Vehicle::find($vehicle->id)->images()->get()->map(function($i){
                    return ['id'=>$i->id,'path'=>$i->path,'is_primary'=>(bool)$i->is_primary];
                })->toArray();
            }
        }

        
        $this->primaryNewIndex = null;
        session()->flash('message', 'Primary image updated.');
        $this->dispatch('notify', ['message' => 'Primary image updated.']);
    }

    public function setPrimaryNew($index)
    {
        $this->primaryNewIndex = (int) $index;
        
    }

    public function removeNewImage($index)
    {
        $index = (int) $index;
        if (! isset($this->images[$index])) {
            return;
        }
        array_splice($this->images, $index, 1);

        
        if ($this->primaryNewIndex !== null) {
            if ($this->primaryNewIndex === $index) {
                $this->primaryNewIndex = null;
            } elseif ($this->primaryNewIndex > $index) {
                $this->primaryNewIndex -= 1;
            }
        }
        $this->dispatch('notify', ['message' => 'Preview removed.']);
    }

    private function resetInputFields()
    {
        $this->vehicleId = null;
        $this->vehicle = null;
        $this->make = '';
        $this->model = '';
        $this->year = date('Y');
        $this->type = '';
        $this->price = '';
        $this->availability_status = 'available';
        $this->images = [];
        $this->license_plate_number = '';
        $this->registration_number = '';
        $this->fuel_type = null;
        $this->transmission_type = null;
        $this->mileage = '';
        $this->seating_capacity = null;
        $this->engine_or_chassis_number = '';
        $this->has_air_conditioning = false;
        $this->has_bluetooth = false;
        $this->description = '';
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function render()
    {
        return view('livewire.vehicles.vehicle-manager', [
            'vehicles' => Vehicle::with('images')->latest()->paginate(10),
        ]);
    }
}
