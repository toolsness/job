<?php

namespace App\Livewire\BusinessOperator;

use Livewire\Component;
use App\Models\VRContent;
use Livewire\WithPagination;

class VRContentList extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $vrContents = VRContent::where('content_name', 'like', '%' . $this->search . '%')
            ->orWhere('content_category', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.business-operator.v-r-content-list', [
            'vrContents' => $vrContents,
        ]);
    }
}
