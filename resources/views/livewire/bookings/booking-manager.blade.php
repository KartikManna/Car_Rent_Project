<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">
                @if (auth()->user()->isAdmin() || auth()->user()->isManager())
                    All Booking Requests
                @else
                    My Bookings
                @endif
            </h2>

            @if (session()->has('message'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
                    {{ session('message') }}
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            @if (auth()->user()->isAdmin() || auth()->user()->isManager())
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    User</th>
                            @endif
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Vehicle</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Dates</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            @if (auth()->user()->isAdmin() || auth()->user()->isManager())
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($bookings as $booking)
                            <tr class="hover:bg-gray-50">
                                @if (auth()->user()->isAdmin() || auth()->user()->isManager())
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $booking->user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $booking->user->email }}</div>
                                    </td>
                                @endif
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $booking->vehicle->make }}
                                        {{ $booking->vehicle->model }}</div>
                                    <div class="text-xs text-gray-500">{{ $booking->vehicle->type }} -
                                        {{ $booking->vehicle->year }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($booking->start_date)->format('M d, Y') }} -
                                    {{ \Carbon\Carbon::parse($booking->end_date)->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
                                    @php
                                        $days = \Carbon\Carbon::parse($booking->start_date)->diffInDays(
                                            \Carbon\Carbon::parse($booking->end_date),
                                        );
                                        if ($days == 0) {
                                            $days = 1;
                                        }
                                    @endphp
                                    ${{ number_format($days * $booking->vehicle->price, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                            {{ $booking->status === 'approved'
                                                                ? 'bg-green-100 text-green-800'
                                                                : ($booking->status === 'pending'
                                                                    ? 'bg-yellow-100 text-yellow-800'
                                                                    : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                                @if (auth()->user()->isAdmin() || auth()->user()->isManager())
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        @if ($booking->status === 'pending')
                                            <button wire:click="approve({{ $booking->id }})"
                                                class="text-green-600 hover:text-green-900 mr-3 font-bold">Approve</button>
                                            <button wire:click="reject({{ $booking->id }})"
                                                class="text-red-600 hover:text-red-900 font-bold">Reject</button>
                                        @else
                                            <span class="text-gray-400 italic">Processed</span>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">No bookings found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $bookings->links() }}
            </div>
        </div>
    </div>
</div>
