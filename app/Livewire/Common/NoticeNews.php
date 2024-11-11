<?php

namespace App\Livewire\Common;

use Livewire\Attributes\Url;
use Livewire\Component;
use App\Models\Notice;
use App\Models\News;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class NoticeNews extends Component
{
    #[Url]
    public string $tab = 'notice';

    public $showContentModal = false;
    public $fullContent = '';
    public $contentType = '';
    public $contentDate = '';

    public function mount()
    {
        $this->dispatch('content-changed', tab: $this->tab);
    }

    public function setTab($value)
    {
        $this->tab = $value;
        $this->dispatch('content-changed', tab: $value);
    }

    public function showFullContent($content, $date, $type)
    {
        $this->fullContent = base64_decode($content);
        $this->contentType = $type;
        $this->contentDate = Carbon::parse($date)->format('F j, Y, g:i A');
        $this->showContentModal = true;

        $this->dispatch('popup-opened');
    }

    public function closeModal()
    {
        $this->showContentModal = false;
    }

    public function render()
    {
        if (Auth::check()) {
            $userType = Auth::user()->user_type;

            if ($userType === 'BusinessOperator') {
                // Show all news and notices for Business Operators
                $notices = Notice::latest()->take(5)->get();
                $news = News::latest()->take(5)->get();
            } else {
                $for = in_array($userType, ['Student', 'Candidate']) ? 'student' : 'company';
                $notices = Notice::where('for', $for)->orWhere('for', 'public')->latest()->take(5)->get();
                $news = News::where('for', $for)->orWhere('for', 'public')->latest()->take(5)->get();
            }
        } else {
            // For non-authenticated users, show only public news and notices
            $notices = Notice::where('for', 'public')->latest()->take(5)->get();
            $news = News::where('for', 'public')->latest()->take(5)->get();
        }

        return view('livewire.common.notice-news', [
            'notices' => $notices,
            'news' => $news,
        ]);
    }
}
