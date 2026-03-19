<div class="py-12" x-data="{ showLoginModal: false }">
    <!-- Login Modal -->
    <div x-show="showLoginModal"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900 bg-opacity-50 overflow-y-auto"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-cloak>
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 transform transition-all"
            @click.away="showLoginModal = false" x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="scale-95" x-transition:enter-end="scale-100">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 mb-4">
                    <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Login Required</h3>
                <p class="text-gray-500 mb-6">Please log in to your account to book this vehicle and manage your
                    rentals.</p>

                <div class="space-y-3">
                    <a href="{{ route('login') }}"
                        class="block w-full px-4 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition shadow-lg hover:shadow-indigo-200">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}"
                        class="block w-full px-4 py-3 border border-gray-300 text-gray-700 font-bold rounded-xl hover:bg-gray-50 transition">
                        Create Account
                    </a>
                </div>

                <button @click="showLoginModal = false" class="mt-6 text-sm text-gray-400 hover:text-gray-600">
                    Maybe later
                </button>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex flex-col md:flex-row gap-6">
                <!-- Filters Sidebar -->
                <div class="w-full md:w-1/4 space-y-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Filters</h3>

                        <!-- Search -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Search</label>
                            <input type="text" wire:model.live="search" placeholder="Make or model..."
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <!-- Type -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Type</label>
                            <select wire:model.live="type"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">All Types</option>
                                @foreach ($types as $t)
                                    <option value="{{ $t }}">{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Brand -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Brand</label>
                            <select wire:model.live="brand"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">All Brands</option>
                                @foreach ($brands as $b)
                                    <option value="{{ $b }}">{{ $b }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Price Range -->
                        <div class="mb-4 grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Min Price</label>
                                <input type="number" wire:model.live="min_price"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Max Price</label>
                                <input type="number" wire:model.live="max_price"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                        </div>

                        <!-- Availability -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Availability</label>
                            <select wire:model.live="availability"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Any</option>
                                <option value="available">Available</option>
                                <option value="rented">Rented</option>
                                <option value="maintenance">Maintenance</option>
                            </select>
                        </div>

                        <!-- Date Range for availability check -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Check Dates</label>
                            <div class="mt-1 grid grid-cols-2 gap-2">
                                <input type="date" wire:model.live="filter_start_date"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <input type="date" wire:model.live="filter_end_date"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <p class="text-xs text-gray-400 mt-1">Leave empty to check availability for today.</p>
                        </div>
                    </div>
                </div>

                <!-- Vehicle Grid -->
                <div class="w-full md:w-3/4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                        @forelse($vehicles as $vehicle)
                            <div
                                class="bg-gray-50 rounded-xl overflow-hidden shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
                                <div class="p-6 h-full flex flex-col justify-between gap-4">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 mb-2">
                                                {{ $vehicle->type }}
                                            </span>
                                            <h4 class="text-xl font-bold text-gray-900">{{ $vehicle->make }}
                                                {{ $vehicle->model }}</h4>
                                            <p class="text-sm text-gray-500">{{ $vehicle->year }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-lg font-bold text-indigo-600">
                                                ${{ number_format($vehicle->price, 2) }}</p>
                                            <p class="text-xs text-gray-400">per day</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center">

                                        @if ($vehicle->availability_status === 'maintenance')
                                            <span class="flex h-2 w-2 rounded-full bg-yellow-500 mr-2"></span>
                                            <span class="text-sm text-yellow-700 font-medium">
                                                Under Maintenance
                                            </span>
                                        @elseif ($vehicle->availability_status === 'rented')
                                            <span class="flex h-2 w-2 rounded-full bg-gray-500 mr-2"></span>
                                            <span class="text-sm text-gray-700 font-medium">
                                                Currently Rented
                                            </span>
                                        @elseif (!empty($vehicle->is_booked))
                                            <span class="flex h-2 w-2 rounded-full bg-red-500 mr-2"></span>
                                            <span class="text-sm text-red-700 font-medium">
                                                @if (!empty($vehicle->available_from))
                                                    Available from
                                                    {{ \Carbon\Carbon::parse($vehicle->available_from)->format('d M') }}
                                                @else
                                                    Booked
                                                @endif
                                            </span>
                                        @else
                                            <span class="flex h-2 w-2 rounded-full bg-green-500 mr-2"></span>
                                            <span class="text-sm text-green-700 font-medium">
                                                Available Now
                                            </span>
                                        @endif

                                    </div>

                                    @php
                                        $fuel = strtolower(trim($vehicle->fuel_type ?? ''));
                                        $trans = strtolower(trim($vehicle->transmission_type ?? ''));
                                    @endphp

                                    <div class="flex items-center gap-4 text-sm text-gray-600">
                                        <div class="flex-1 text-center">
                                            <div class="flex items-center justify-center mb-1">
                                                <svg class="h-6 w-6 text-gray-500" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.5"
                                                        d="M12 14c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zM6 20v-2a4 4 0 014-4h4a4 4 0 014 4v2" />
                                                </svg>
                                            </div>
                                            <div class="text-xs text-gray-500">Seats</div>
                                            <div class="text-gray-800 font-medium">{{ $vehicle->seating_capacity }}
                                            </div>
                                        </div>

                                        <div class="flex-1 text-center">
                                            <div class="flex items-center justify-center mb-1">
                                                @if (str_contains($fuel, 'electric'))
                                                    <!-- electric plug -->
                                                    <svg class="h-6 w-6 text-green-500" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1.5" d="M13 2v8h3l-4 10v-8H8l5-12z" />
                                                    </svg>
                                                @elseif(str_contains($fuel, 'hybrid'))
                                                    <!-- leaf -->
                                                    <svg class="h-6 w-6 text-green-600" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1.5"
                                                            d="M12 2s4 2 6 6-2 8-6 10c-4-2-6-6-6-10S12 2 12 2z" />
                                                    </svg>
                                                @else
                                                    <!-- fuel pump -->
                                                    <svg class="h-6 w-6 text-gray-500" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1.5"
                                                            d="M3 7h9v13H3zM16 7v6a2 2 0 002 2h1V7h-3z" />
                                                    </svg>
                                                @endif
                                            </div>
                                            <div class="text-xs text-gray-500">Fuel</div>
                                            <div class="text-gray-800 font-medium">{{ $vehicle->fuel_type }}</div>
                                        </div>

                                        <div class="flex-1 text-center">
                                            <div class="flex items-center justify-center mb-1">
                                                @if (str_contains($trans, 'auto'))
                                                    <!-- automatic gear icon -->
                                                    <svg class="h-6 w-6 text-gray-500" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1.5" d="M4 7h16M4 12h16M4 17h16" />
                                                    </svg>
                                                @else
                                                    <!-- manual gear stick -->
                                                    <svg class="h-6 w-6 text-gray-500" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1.5" d="M12 2v6M9 8h6l-1 12H10L9 8z" />
                                                    </svg>
                                                @endif
                                            </div>
                                            <div class="text-xs text-gray-500">Transmission</div>
                                            <div class="text-gray-800 font-medium">{{ $vehicle->transmission_type }}
                                            </div>
                                        </div>
                                    </div>

                                    <div x-data="{ open: false, active: 0, images: [@foreach ($vehicle->images ?? collect() as $i)'{{ Storage::url($i->path) }}', @endforeach] }" class="space-y-3">
                                        @php
                                            $images = $vehicle->images ?? collect();
                                            $primary = $vehicle->image_path
                                                ? Storage::url($vehicle->image_path)
                                                : ($images->first()
                                                    ? Storage::url($images->first()->path)
                                                    : null);
                                        @endphp

                                        @if ($primary)
                                            <img src="{{ $primary }}" @click="open = true"
                                                class="w-full h-40 object-cover rounded mb-2 cursor-pointer">
                                        @else
                                            <div
                                                class="w-full h-40 bg-gray-100 rounded flex items-center justify-center">
                                                <svg class="h-12 w-12 text-gray-300" fill="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path
                                                        d="M21 19V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2zM5 7h14v10H5V7zm7.5-3a1.5 1.5 0 1 1-3.001.001A1.5 1.5 0 0 1 12.5 4zM6.343 16.657l3.182-3.182a1.003 1.003 0 0 1 .707-.293c.266-.001.52.105.707.293l3.182 3.182a1.003 1.003 0 1 1-1.414 1.414L12.5 15.414l-2.879 2.879a1.003 1.003 0 1 1-1.414-1.414z" />
                                                </svg>
                                            </div>
                                        @endif

                                        <div class="flex items-center gap-2">
                                            <button @click="open = true"
                                                class="flex-1 inline-flex items-center justify-center text-sm px-4 py-2 bg-white text-indigo-600 border border-indigo-600 rounded-md hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                                View Details
                                            </button>

                                            @auth
                                                @if ($vehicle->availability_status === 'available')
                                                    <a href="{{ route('bookings.create', ['vehicle_id' => $vehicle->id]) }}"
                                                        class="inline-flex items-center justify-center text-sm px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v11a2 2 0 002 2z">
                                                            </path>
                                                        </svg>
                                                        Book
                                                    </a>
                                                @else
                                                    <button disabled
                                                        class="inline-flex items-center justify-center text-sm px-4 py-2 bg-gray-200 text-gray-500 rounded-md cursor-not-allowed">Not
                                                        Available</button>
                                                @endif
                                            @else
                                                <button @click="showLoginModal = true"
                                                    class="inline-flex items-center justify-center text-sm px-4 py-2 border border-indigo-600 text-indigo-600 rounded-md hover:bg-indigo-50">
                                                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v11a2 2 0 002 2z">
                                                        </path>
                                                    </svg>
                                                    Book
                                                </button>
                                            @endauth
                                        </div>


                                        <div x-show="open" x-cloak
                                            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50">
                                            <div
                                                class="bg-white rounded-lg shadow-xl max-w-4xl w-full p-6 overflow-hidden">
                                                <div class="flex justify-between items-start mb-4">
                                                    <div>
                                                        <h3 class="text-xl font-bold">{{ $vehicle->make }}
                                                            {{ $vehicle->model }}</h3>
                                                        <p class="text-sm text-gray-500">{{ $vehicle->year }} •
                                                            {{ $vehicle->type }}</p>
                                                    </div>
                                                    <button @click="open = false"
                                                        class="text-gray-500 hover:text-gray-700 rounded-md px-3 py-1 bg-gray-50">Close</button>
                                                </div>

                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                    <div>
                                                        <div class="mb-3">
                                                            <template x-if="images.length">
                                                                <img :src="images[active]"
                                                                    class="w-full h-64 object-cover rounded"
                                                                    alt="Vehicle image">
                                                            </template>
                                                            <template x-if="!images.length">
                                                                @if ($vehicle->image_path)
                                                                    <img src="{{ Storage::url($vehicle->image_path) }}"
                                                                        class="w-full h-64 object-cover rounded"
                                                                        alt="">
                                                                @else
                                                                    <div
                                                                        class="w-full h-64 bg-gray-100 rounded flex items-center justify-center">
                                                                        No images</div>
                                                                @endif
                                                            </template>
                                                        </div>

                                                        @if ($images->isNotEmpty())
                                                            <div class="flex gap-2 overflow-x-auto">
                                                                @foreach ($images as $i)
                                                                    <button type="button"
                                                                        @click="active = {{ $loop->index }}"
                                                                        class="h-16 w-24 flex-none rounded overflow-hidden border-2"
                                                                        :class="{ 'ring-2 ring-indigo-500': active ===
                                                                                {{ $loop->index }} }">
                                                                        <img src="{{ Storage::url($i->path) }}"
                                                                            class="h-full w-full object-cover"
                                                                            alt="">
                                                                    </button>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <div>
                                                        <div class="mb-3 grid grid-cols-3 gap-3">
                                                            <div class="text-center">
                                                                <div class="flex items-center justify-center mb-1">
                                                                    <svg class="h-6 w-6 text-gray-500" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24"
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="1.5"
                                                                            d="M12 14c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zM6 20v-2a4 4 0 014-4h4a4 4 0 014 4v2" />
                                                                    </svg>
                                                                </div>
                                                                <div class="text-xs text-gray-500">Seats</div>
                                                                <div class="text-gray-800 font-medium">
                                                                    {{ $vehicle->seating_capacity }}</div>
                                                            </div>

                                                            <div class="text-center">
                                                                <div class="flex items-center justify-center mb-1">
                                                                    @php $fuel = strtolower(trim($vehicle->fuel_type ?? '')); @endphp
                                                                    @if (str_contains($fuel, 'electric'))
                                                                        <svg class="h-6 w-6 text-green-500"
                                                                            fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24"
                                                                            xmlns="http://www.w3.org/2000/svg">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="1.5"
                                                                                d="M13 2v8h3l-4 10v-8H8l5-12z" />
                                                                        </svg>
                                                                    @elseif(str_contains($fuel, 'hybrid'))
                                                                        <svg class="h-6 w-6 text-green-600"
                                                                            fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24"
                                                                            xmlns="http://www.w3.org/2000/svg">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="1.5"
                                                                                d="M12 2s4 2 6 6-2 8-6 10c-4-2-6-6-6-10S12 2 12 2z" />
                                                                        </svg>
                                                                    @else
                                                                        <svg class="h-6 w-6 text-gray-500"
                                                                            fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24"
                                                                            xmlns="http://www.w3.org/2000/svg">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="1.5"
                                                                                d="M3 7h9v13H3zM16 7v6a2 2 0 002 2h1V7h-3z" />
                                                                        </svg>
                                                                    @endif
                                                                </div>
                                                                <div class="text-xs text-gray-500">Fuel</div>
                                                                <div class="text-gray-800 font-medium">
                                                                    {{ $vehicle->fuel_type }}</div>
                                                            </div>

                                                            <div class="text-center">
                                                                <div class="flex items-center justify-center mb-1">
                                                                    @php $trans = strtolower(trim($vehicle->transmission_type ?? '')); @endphp
                                                                    @if (str_contains($trans, 'auto'))
                                                                        <svg class="h-6 w-6 text-gray-500"
                                                                            fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24"
                                                                            xmlns="http://www.w3.org/2000/svg">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="1.5"
                                                                                d="M4 7h16M4 12h16M4 17h16" />
                                                                        </svg>
                                                                    @else
                                                                        <svg class="h-6 w-6 text-gray-500"
                                                                            fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24"
                                                                            xmlns="http://www.w3.org/2000/svg">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="1.5"
                                                                                d="M12 2v6M9 8h6l-1 12H10L9 8z" />
                                                                        </svg>
                                                                    @endif
                                                                </div>
                                                                <div class="text-xs text-gray-500">Transmission</div>
                                                                <div class="text-gray-800 font-medium">
                                                                    {{ $vehicle->transmission_type }}</div>
                                                            </div>
                                                        </div>

                                                        <p class="text-gray-700 mb-3">
                                                            {{ $vehicle->description ?? 'No description provided.' }}
                                                        </p>
                                                        <p class="text-lg font-semibold text-indigo-600 mb-4">
                                                            ${{ number_format($vehicle->price, 2) }} <span
                                                                class="text-sm text-gray-400">per day</span></p>

                                                        <div class="space-y-3">
                                                            <a href="{{ route('bookings.create', ['vehicle_id' => $vehicle->id]) }}"
                                                                class="block w-full text-center px-4 py-2 bg-indigo-600 text-white rounded">Book
                                                                Now</a>
                                                            @if (\Illuminate\Support\Facades\Route::has('vehicles.show'))
                                                                <a href="{{ route('vehicles.show', ['vehicle' => $vehicle->id]) }}"
                                                                    class="block w-full text-center px-4 py-2 border border-gray-300 text-gray-700 rounded">View
                                                                    Full Details</a>
                                                            @else
                                                                <a href="{{ route('vehicles.index') }}"
                                                                    class="block w-full text-center px-4 py-2 border border-gray-300 text-gray-700 rounded">View
                                                                    Listings</a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @auth
                                        @if ($vehicle->availability_status === 'available')
                                            <a href="{{ route('bookings.create', ['vehicle_id' => $vehicle->id]) }}"
                                                class="block w-full text-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                                Book Now
                                            </a>
                                        @else
                                            <button disabled
                                                class="block w-full text-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-gray-400 bg-gray-200 cursor-not-allowed">
                                                Not Available
                                            </button>
                                        @endif
                                    @else
                                        <button @click="showLoginModal = true"
                                            class="block w-full text-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-50 hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                            Book Now
                                        </button>
                                    @endauth
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-12 text-center text-gray-500">
                                No vehicles found matching your criteria.
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-8">
                        {{ $vehicles->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
