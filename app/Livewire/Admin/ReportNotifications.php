<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ReportNotifications extends Component
{
    public $notifications;
    public $unreadCount = 0;

    protected $listeners = ['notificationRead' => 'loadNotifications'];

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        $this->notifications = Auth::user()
            ->notifications()
            ->where('type', 'App\Notifications\NewReportSubmitted')
            ->latest()
            ->take(10)
            ->get();

        $this->unreadCount = Auth::user()
            ->unreadNotifications()
            ->where('type', 'App\Notifications\NewReportSubmitted')
            ->count();
    }

    public function markAsRead($notificationId)
    {
        Auth::user()
            ->notifications()
            ->where('id', $notificationId)
            ->first()
            ?->markAsRead();

        $this->loadNotifications();
        $this->dispatch('notificationRead');
    }

    public function markAllAsRead()
    {
        Auth::user()
            ->unreadNotifications()
            ->where('type', 'App\Notifications\NewReportSubmitted')
            ->update(['read_at' => now()]);

        $this->loadNotifications();
        $this->dispatch('notificationRead');
    }

    public function render()
    {
        return view('livewire.admin.report-notifications');
    }
}
