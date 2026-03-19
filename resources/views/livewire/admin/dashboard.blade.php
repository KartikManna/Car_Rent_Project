<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            
            <div
                class="bg-gradient-to-br from-indigo-600 to-indigo-700 rounded-2xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-2 bg-indigo-500 bg-opacity-30 rounded-lg text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-indigo-100 text-sm font-medium">Estimated Revenue</h3>
                <p class="text-3xl font-extrabold mt-1">${{ number_format($totalRevenue, 2) }}</p>
            </div>

            
            <div
                class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 transform hover:scale-105 transition-transform duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-2 bg-blue-100 rounded-lg text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-gray-500 text-sm font-medium">Total Bookings</h3>
                <p class="text-3xl font-extrabold text-gray-900 mt-1">{{ $totalBookings }}</p>
            </div>

            
            <div
                class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 transform hover:scale-105 transition-transform duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-2 bg-green-100 rounded-lg text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-gray-500 text-sm font-medium">Vehicle Size</h3>
                <p class="text-3xl font-extrabold text-gray-900 mt-1">{{ $totalVehicles }}</p>
            </div>

            
            <div
                class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 transform hover:scale-105 transition-transform duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-2 bg-purple-100 rounded-lg text-purple-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-gray-500 text-sm font-medium">Customers</h3>
                <p class="text-3xl font-extrabold text-gray-900 mt-1">{{ $totalCustomers }}</p>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl p-6 border border-gray-100">
            <h3 class="text-lg font-bold text-gray-900 mb-6 border-b border-gray-100 pb-4">Recent Activity</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th
                                class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                User</th>
                            <th
                                class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Vehicle</th>
                            <th
                                class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th
                                class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($recentBookings as $booking)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-gray-900">{{ $booking->user->name }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-600">{{ $booking->vehicle->make }}
                                        {{ $booking->vehicle->model }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full {{ $booking->status === 'approved' ? 'bg-green-100 text-green-800' : ($booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                    {{ $booking->created_at->diffForHumans() }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-400">No recent bookings found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>