<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class UnreadMessageCount extends Component
{
    public $unreadCount = 0;

    protected $listeners = ['messageRead' => 'updateCount'];

    public function mount()
    {
        $this->updateCount();
    }

    public function updateCount()
    {
        $this->unreadCount = Message::where('receiver_user_id', Auth::id())
            ->whereNull('read_at')
            ->count();
    }

    public function render()
    {
        return view('livewire.unread-message-count');
    }
}
