<?php

namespace App\Livewire\BusinessOperator;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\News;
use App\Models\Notice;

class NewsNoticeManager extends Component
{
    use WithPagination;

    public $showPopup = false;
    public $editMode = false;
    public $itemType = 'news';
    public $content = '';
    public $for = 'student';
    public $editingId;

    protected $rules = [
        'content' => 'required|min:10',
        'for' => 'required|in:student,company,public',
    ];

    public function render()
    {
        $news = News::latest()->paginate(5, ['*'], 'newsPage');
        $notices = Notice::latest()->paginate(5, ['*'], 'noticePage');

        return view('livewire.business-operator.news-notice-manager', [
            'news' => $news,
            'notices' => $notices,
        ])->layout('layouts.app');
    }

    public function openPopup($type, $id = null)
    {
        $this->resetValidation();
        $this->itemType = $type;
        $this->showPopup = true;
        $this->editMode = $id !== null;
        $this->editingId = $id;

        if ($this->editMode) {
            $item = $this->itemType === 'news' ? News::find($id) : Notice::find($id);
            $this->content = $item->content;
            $this->for = $item->for;
        } else {
            $this->reset(['content', 'for']);
        }

        $this->dispatch('popup-opened');
    }

    public function closePopup()
    {
        $this->showPopup = false;
        $this->editMode = false;
        $this->reset(['content', 'for', 'editingId']);
        $this->dispatch('popup-closed');
    }

    public function save()
    {
        $this->validate();

        if ($this->editMode) {
            $item = $this->itemType === 'news' ? News::find($this->editingId) : Notice::find($this->editingId);
            $item->update([
                'content' => $this->content,
                'for' => $this->for,
            ]);
            $message = ucfirst($this->itemType) . ' updated successfully.';
        } else {
            $model = $this->itemType === 'news' ? News::class : Notice::class;
            $model::create([
                'content' => $this->content,
                'for' => $this->for,
            ]);
            $message = ucfirst($this->itemType) . ' created successfully.';
        }

        $this->closePopup();
        flash()->success($message);
    }

    public function delete($id)
    {
        $model = $this->itemType === 'news' ? News::class : Notice::class;
        $model::destroy($id);
        flash()->success(ucfirst($this->itemType) . ' deleted successfully.');
    }
}
