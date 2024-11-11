<?php

namespace App\Livewire\BusinessOperator;

use Livewire\Component;
use App\Models\BusinessOperator;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class BusinessOperatorList extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';

    #[Url]
    public $tagFilter = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingTagFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $businessOperators = BusinessOperator::query()
            ->with('user')
            ->when($this->tagFilter, function ($query) {
                return $query->where('tag', $this->tagFilter);
            })
            ->where(function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                })
                ->orWhere('name_kanji', 'like', '%' . $this->search . '%')
                ->orWhere('name_katakana', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);

        return view('livewire.business-operator.business-operator-list', [
            'businessOperators' => $businessOperators,
        ]);
    }
}
