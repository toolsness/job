<?php

namespace App\Livewire\BusinessOperator;

use Livewire\Component;
use App\Models\Company;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class CompanyList extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $companies = Company::query()
            ->with('industryType')
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('name_kanji', 'like', '%' . $this->search . '%')
                    ->orWhere('name_katakana', 'like', '%' . $this->search . '%')
                    ->orWhereHas('industryType', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->paginate(10);

        return view('livewire.business-operator.company-list', [
            'companies' => $companies,
        ]);
    }
}
