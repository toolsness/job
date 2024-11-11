<?php

namespace App\Livewire\Company;

use Livewire\Component;
use App\Models\VRContent;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class VRContentList extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $user = Auth::user();
        $company = $user->companyAdmin?->company ?? $user->companyRepresentative?->company;

        $query = VRContent::where('company_id', $company->id)
            ->when($this->search, function ($query) {
                return $query->where('content_name', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter, function ($query) {
                return $query->where('status', $this->statusFilter);
            });

        $vrContents = $query->paginate(10);

        return view('livewire.company.v-r-content-list', [
            'vrContents' => $vrContents,
            'statuses' => VRContent::getStatuses(),
        ]);
    }
}
