<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900 border-b-4 border-indigo-500 pb-2 inline-block">Detailed
                    Reports</h2>
                <button wire:click="exportCSV"
                    class="px-6 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 shadow-lg hover:shadow-green-200 transition-all font-bold">
                    Export to CSV
                </button>
            </div>

            <div
                class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8 bg-gray-50 p-6 rounded-2xl border border-gray-100 shadow-inner">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">From Date</label>
                    <input type="date" wire:model.live="dateFrom"
                        class="w-full rounded-xl border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">To Date</label>
                    <input type="date" wire:model.live="dateTo"
                        class="w-full rounded-xl border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
                    <select wire:model.live="status"
                        class="w-full rounded-xl border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
            </div>

            <div class="overflow-x-auto rounded-2xl border border-gray-100">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 font-bold">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs text-gray-500 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-3 text-left text-xs text-gray-500 uppercase tracking-wider">Vehicle</th>
                            <th class="px-6 py-3 text-left text-xs text-gray-500 uppercase tracking-wider">Start Date
                            </th>
                            <th class="px-6 py-3 text-left text-xs text-gray-500 uppercase tracking-wider">End Date</th>
                            <th class="px-6 py-3 text-left text-xs text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($bookings as $booking)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#{{ $booking->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                    {{ $booking->user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $booking->vehicle->make }}
                                    {{ $booking->vehicle->model }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $booking->start_date }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $booking->end_date }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span
                                        class="px-2 py-1 rounded-full font-bold text-xs {{ $booking->status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-8">
                {{ $bookings->links() }}
            </div>
        </div>
    </div>
</div>