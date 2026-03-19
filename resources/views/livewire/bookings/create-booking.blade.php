<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-3xl font-bold text-gray-900 border-b-4 border-indigo-500 pb-2">Complete Your Booking
                </h2>
                <a href="{{ route('vehicles.index') }}"
                    class="text-sm text-indigo-600 hover:text-indigo-800 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Vehicles
                </a>
            </div>

            @php
                $images = $vehicle->images ?? collect();
                $primary = $vehicle->image_path
                    ? Storage::url($vehicle->image_path)
                    : ($images->first() ? Storage::url($images->first()->path) : null);
                $fuel = strtolower(trim($vehicle->fuel_type ?? ''));
                $trans = strtolower(trim($vehicle->transmission_type ?? ''));
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 shadow-inner">
                    @if($primary)
                        <img src="{{ $primary }}"
                            class="w-full h-48 object-cover rounded-xl mb-4" alt="{{ $vehicle->make }} {{ $vehicle->model }}">
                    @else
                        <div class="w-full h-48 bg-gray-100 rounded-xl mb-4 flex items-center justify-center text-gray-400">No image</div>
                    @endif

                    

                    <h3 class="text-indigo-800 text-xs font-bold uppercase tracking-widest mb-2">{{ $vehicle->type }}</h3>
                    <h4 class="text-2xl font-bold text-gray-900 mb-1">{{ $vehicle->make }} {{ $vehicle->model }}</h4>
                    <p class="text-gray-500 text-sm mb-3">{{ $vehicle->year }} Edition</p>

                    <div class="flex items-center gap-4 text-sm text-gray-600 mb-4">
                        <div class="flex items-center gap-2">
                            <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zM6 20v-2a4 4 0 014-4h4a4 4 0 014 4v2"/></svg>
                            <span class="text-gray-800 font-medium">{{ $vehicle->seating_capacity ?? '—' }}</span>
                        </div>

                        <div class="flex items-center gap-2">
                            @if(str_contains($fuel, 'electric'))
                                <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 2v8h3l-4 10v-8H8l5-12z"/></svg>
                            @elseif(str_contains($fuel, 'hybrid'))
                                <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 2s4 2 6 6-2 8-6 10c-4-2-6-6-6-10S12 2 12 2z"/></svg>
                            @else
                                <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7h9v13H3zM16 7v6a2 2 0 002 2h1V7h-3z"/></svg>
                            @endif
                            <span class="text-gray-800 font-medium">{{ $vehicle->fuel_type ?? '—' }}</span>
                        </div>

                        <div class="flex items-center gap-2">
                            @if(str_contains($trans, 'auto'))
                                <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 7h16M4 12h16M4 17h16"/></svg>
                            @else
                                <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 2v6M9 8h6l-1 12H10L9 8z"/></svg>
                            @endif
                            <span class="text-gray-800 font-medium">{{ $vehicle->transmission_type ?? '—' }}</span>
                        </div>
                    </div>

                    <div class="text-sm text-gray-700 mb-4">{{ $vehicle->description ?? 'No description provided.' }}</div>

                    <div class="flex items-center justify-between py-3 border-t border-gray-200">
                        <span class="text-gray-600">Daily Rate</span>
                        <span class="text-lg font-bold text-indigo-600">${{ number_format($vehicle->price, 2) }}</span>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-200">
                        {{-- <div class="flex items-center text-sm text-gray-500">
                            <span class="w-2 h-2 rounded-full bg-green-400 mr-2"></span>
                            Free cancellation within 24 hours
                        </div> --}}
                        <div class="flex items-center text-sm text-gray-500 mt-2">
                            <span class="w-2 h-2 rounded-full bg-blue-400 mr-2"></span>
                            Unlimited mileage included
                        </div>
                    </div>
                </div>

                <!-- Booking Form -->
                <form wire:submit.prevent="book" class="space-y-6">
                    @if (session()->has('error'))
                        <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded shadow-sm">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Pickup Date</label>
                            <input type="date" wire:model.live="start_date"
                                class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-all duration-200">
                            @error('start_date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Return Date</label>
                            <input type="date" wire:model.live="end_date"
                                class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-all duration-200">
                            @error('end_date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="bg-indigo-50 rounded-2xl p-6 border border-indigo-100 shadow-inner">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-gray-600 font-medium">Estimated Total</span>
                            <span
                                class="text-3xl font-extrabold text-indigo-700">${{ number_format($total_price, 2) }}</span>
                        </div>
                        <p class="text-xs text-indigo-500 text-right">Tax and insurance included</p>
                    </div>

                    <button type="submit"
                        class="w-full bg-indigo-600 text-white font-bold py-4 px-6 rounded-2xl hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transform hover:scale-[1.02] active:scale-[0.98] transition-all duration-200 shadow-lg hover:shadow-indigo-200">
                        Confirm Booking Request
                    </button>
                </form>
            </div>

            <p class="text-center text-gray-400 text-xs mt-8">
                By clicking "Confirm Booking Request", you agree to our Terms of Service and Privacy Policy.
            </p>
        </div>
    </div>
</div>