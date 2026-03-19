<?php

namespace App\Livewire\Notifications;

use App\Models\Notification;
use Livewire\Component;
use Livewire\WithPagination;

class NotificationList extends Component
{
    use WithPagination;

    public function markAsRead($id)
    {
        Notification::where('id', $id)->where('user_id', auth()->id())->update(['read_status' => true]);
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', auth()->id())->update(['read_status' => true]);
    }

    public function render()
    {
        return view('livewire.notifications.notification-list', [
            'notifications' => Notification::where('user_id', auth()->id())->latest()->paginate(10)
        ]);
    }
}
