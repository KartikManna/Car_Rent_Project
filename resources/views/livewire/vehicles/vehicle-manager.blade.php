<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800">Manage Vehicles</h2>
                <button wire:click="create"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">Add
                    Vehicle</button>
            </div>

            @if (session()->has('message'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm"
                    role="alert">
                    <p>{{ session('message') }}</p>
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Vehicle</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Year</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Price/Day</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Image</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($vehicles as $v)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $v->make }} {{ $v->model }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $v->type }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $v->year }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
                                    ${{ number_format($v->price, 2) }}</td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="relative inline-block">
                                        @if ($v->image_path)
                                            <img src="{{ Storage::url($v->image_path) }}"
                                                alt="{{ $v->make }} {{ $v->model }}"
                                                class="h-auto w-12 object-contain rounded transform scale-100 hover:scale-105 transition duration-300">

                                            {{-- @if(isset($v->images) && $v->images->where('is_primary', true)->count())
                                                <span class="absolute -left-1 -top-1 bg-indigo-600 text-white text-[10px] px-1 rounded">Primary</span>
                                            @endif --}}
                                        @else
                                            {{-- Image not available  icon --}}
                                            <svg class="h-12 w-12 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M21 19V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2zM5 7h14v10H5V7zm7.5-3a1.5 1.5 0 1 1-3.001.001A1.5 1.5 0 0 1 12.5 4zM6.343 16.657l3.182-3.182a1.003 1.003 0 0 1 .707-.293c.266-.001.52.105.707.293l3.182 3.182a1.003 1.003 0 1 1-1.414 1.414L12.5 15.414l-2.879 2.879a1.003 1.003 0 1 1-1.414-1.414z" />
                                            </svg>
                                            <span class="text-gray-500 text-xs">No Image</span>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full {{ $v->availability_status === 'available' ? 'bg-green-100 text-green-800' : ($v->availability_status === 'maintenance' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($v->availability_status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button wire:click="edit({{ $v->id }})"
                                        class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                    <button wire:click="delete({{ $v->id }})"
                                        onclick="confirm('Are you sure?') || event.stopImmediatePropagation()"
                                        class="text-red-600 hover:text-red-900">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $vehicles->links() }}
            </div>
        </div>
    </div>

    <!-- Modal -->
    @if ($isOpen)
        <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                    wire:click="closeModal"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div
                    class="inline-block align-middle bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form wire:submit.prevent="save">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="flex items-start justify-between mb-4">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    {{ $vehicleId ? 'Edit Vehicle' : 'Add New Vehicle' }}
                                </h3>
                                <button type="button" wire:click="closeModal" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <span class="sr-only">Close</span>
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Make</label>
                                    <input type="text" wire:model="make"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    @error('make')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Model</label>
                                    <input type="text" wire:model="model"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    @error('model')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Year</label>
                                        <input type="number" wire:model="year"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        @error('year')
                                            <span class="text-red-500 text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Type</label>
                                        <input type="text" wire:model="type"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        @error('type')
                                            <span class="text-red-500 text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mt-4 grid grid-cols-1 gap-4">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">License Plate
                                                Number</label>
                                            <input type="text" wire:model="license_plate_number"
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            @error('license_plate_number')
                                                <span class="text-red-500 text-xs">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Registration
                                                Number</label>
                                            <input type="text" wire:model="registration_number"
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            @error('registration_number')
                                                <span class="text-red-500 text-xs">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Fuel Type</label>
                                            <select wire:model="fuel_type"
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                <option value="">Select fuel</option>
                                                <option value="petrol">Petrol</option>
                                                <option value="diesel">Diesel</option>
                                                <option value="electric">Electric</option>
                                                <option value="hybrid">Hybrid</option>
                                            </select>
                                            @error('fuel_type')
                                                <span class="text-red-500 text-xs">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Transmission</label>
                                            <select wire:model="transmission_type"
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                <option value="">Select transmission</option>
                                                <option value="manual">Manual</option>
                                                <option value="automatic">Automatic</option>
                                            </select>
                                            @error('transmission_type')
                                                <span class="text-red-500 text-xs">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Mileage</label>
                                            <input type="text" wire:model="mileage"
                                                placeholder="e.g. 15 km/l or 300 km/charge"
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <p class="mt-1 text-xs text-gray-500">Unit hint: km/l (fuel) or km/charge
                                                (electric)</p>
                                            @error('mileage')
                                                <span class="text-red-500 text-xs">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Seating
                                                Capacity</label>
                                            <input type="number" wire:model="seating_capacity" min="1"
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            @error('seating_capacity')
                                                <span class="text-red-500 text-xs">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-span-2">
                                            <label class="block text-sm font-medium text-gray-700">Engine / Chassis
                                                Number</label>
                                            <input type="text" wire:model="engine_or_chassis_number"
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            @error('engine_or_chassis_number')
                                                <span class="text-red-500 text-xs">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Optional Features</h3>

                                        <div class="flex flex-wrap gap-4">

                                            <label
                                                class="flex items-center gap-3 px-4 py-2 border rounded-lg cursor-pointer hover:bg-gray-50 transition">
                                                <input type="checkbox" wire:model="has_air_conditioning"
                                                    class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">

                                                <span class="text-sm text-gray-700 font-medium">
                                                    Air Conditioning
                                                </span>
                                            </label>


                                            <label
                                                class="flex items-center gap-3 px-4 py-2 border rounded-lg cursor-pointer hover:bg-gray-50 transition">
                                                <input type="checkbox" wire:model="has_bluetooth"
                                                    class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">

                                                <span class="text-sm text-gray-700 font-medium">
                                                    Bluetooth / Infotainment
                                                </span>
                                            </label>

                                        </div>

                                        @error('has_air_conditioning')
                                            <span class="text-red-500 text-xs">{{ $message }}</span>
                                        @enderror

                                        @error('has_bluetooth')
                                            <span class="text-red-500 text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Vehicle
                                            Description</label>
                                        <textarea wire:model="description" rows="6"
                                            placeholder="Write a short description of the vehicle (features, condition, history, etc.)"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm resize-y"></textarea>
                                        @error('description')
                                            <span class="text-red-500 text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Price/Day</label>
                                        <div class="relative mt-1 rounded-md shadow-sm">
                                            <div
                                                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                <span class="text-gray-500 sm:text-sm">$</span>
                                            </div>
                                            <input type="number" wire:model="price" step="0.01"
                                                class="block w-full rounded-md border-gray-300 pl-7 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        </div>
                                        @error('price')
                                            <span class="text-red-500 text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Status</label>
                                        <select wire:model="availability_status"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <option value="available">Available</option>
                                            <option value="rented">Rented</option>
                                            <option value="maintenance">Maintenance</option>
                                        </select>
                                        @error('availability_status')
                                            <span class="text-red-500 text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-span-2">
                                        <label class="block text-sm font-medium text-gray-700">Images</label>
                                        <input type="file" wire:model="images" multiple
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        @error('images.*')
                                            <span class="text-red-500 text-xs">{{ $message }}</span>
                                        @enderror

                                            <script>
                                                window.addEventListener('notify', event => {
                                                    alert(event.detail.message);
                                                });
                                            </script>

                                       
                                        @if (!empty($images))
                                            <div class="mt-2 flex flex-wrap gap-2">
                                                @foreach ($images as $img)
                                                    <div wire:key="new-{{ $loop->index }}" class="relative w-24 h-24 overflow-visible rounded-md border">
                                                        <img src="{{ $img->temporaryUrl() }}" alt="Preview" class="w-full h-full object-cover">

                                                        @if($primaryNewIndex !== null && $primaryNewIndex == $loop->index)
                                                            <span class="absolute left-1 top-1 bg-indigo-600 text-white text-xs px-1 rounded">Primary</span>
                                                        @else
                                                            <button type="button" wire:click.prevent="setPrimaryNew({{ $loop->index }})" class="absolute left-1 top-1 bg-white bg-opacity-90 rounded-full p-1 text-yellow-400 hover:text-yellow-500 shadow z-20">
                                                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.966a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.176 0l-3.38 2.455c-.784.57-1.84-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.045 9.393c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69l1.286-3.966z" />
                                                                </svg>
                                                            </button>
                                                        @endif

                                                        <button type="button" wire:key="remove-new-{{ $loop->index }}" wire:click.prevent="removeNewImage({{ $loop->index }})" class="absolute top-1 right-1 bg-white bg-opacity-70 rounded-full p-0.5 text-red-600 hover:text-red-800">
                                                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif

                                        
                                        @if (!empty($existingImages))
                                            <div class="mt-2 flex flex-wrap gap-2">
                                                @foreach ($existingImages as $img)
                                                    <div wire:key="existing-{{ $img['id'] }}" class="relative w-24 h-24 overflow-visible rounded-md border">
                                                        <img src="{{ Storage::url($img['path']) }}" alt="Image" class="w-full h-full object-cover">

                                                        @if($img['is_primary'])
                                                            <span class="absolute left-1 top-1 bg-indigo-600 text-white text-xs px-1 rounded">Primary</span>
                                                        @else
                                                            <button type="button" wire:click="setPrimaryImage({{ $img['id'] }})" onclick="confirm('Set this image as primary?') || event.stopImmediatePropagation()" class="absolute left-1 top-1 bg-white bg-opacity-90 rounded-full p-1 text-yellow-400 hover:text-yellow-500 shadow z-20">
                                                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.966a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.176 0l-3.38 2.455c-.784.57-1.84-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.045 9.393c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69l1.286-3.966z" />
                                                                </svg>
                                                            </button>
                                                        @endif

                                                        <button type="button" wire:key="delete-existing-{{ $img['id'] }}" wire:click="deleteImage({{ $img['id'] }})" onclick="confirm('Delete this image?') || event.stopImmediatePropagation()" class="absolute top-1 right-1 bg-white bg-opacity-70 rounded-full p-0.5 text-red-600 hover:text-red-800">
                                                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                    <button type="submit"
                                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm transition">Save</button>
                                    <button wire:click="closeModal" type="button"
                                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition">Cancel</button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
