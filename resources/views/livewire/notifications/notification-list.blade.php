<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900 border-b-4 border-indigo-500 pb-2 inline-block">Your
                    Notifications</h2>
                @if($notifications->where('read_status', false)->count() > 0)
                    <button wire:click="markAllAsRead"
                        class="text-sm text-indigo-600 hover:text-indigo-800 font-semibold transition">Mark all as
                        read</button>
                @endif
            </div>

            <div class="space-y-4">
                @forelse($notifications as $notification)
                    <div
                        class="p-4 rounded-xl border {{ $notification->read_status ? 'bg-white border-gray-100' : 'bg-indigo-50 border-indigo-100 shadow-sm' }} transition-all duration-300">
                        <div class="flex justify-between items-start">
                            <div class="flex space-x-3">
                                <div class="shrink-0 pt-1">
                                    @if($notification->type === 'booking_request')
                                        <div class="p-2 bg-blue-100 rounded-lg text-blue-600">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                    @elseif($notification->type === 'booking_status')
                                        <div class="p-2 bg-green-100 rounded-lg text-green-600">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                    @else
                                        <div class="p-2 bg-gray-100 rounded-lg text-gray-600">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 00-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                                </path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <p
                                        class="text-sm font-medium {{ $notification->read_status ? 'text-gray-600' : 'text-gray-900 font-bold' }}">
                                        {{ $notification->message }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                            @if(!$notification->read_status)
                                <button wire:click="markAsRead({{ $notification->id }})"
                                    class="text-xs text-gray-400 hover:text-indigo-600 transition">
                                    Mark as read
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 text-gray-500 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 00-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                            </path>
                        </svg>
                        No notifications to display.
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
</div>