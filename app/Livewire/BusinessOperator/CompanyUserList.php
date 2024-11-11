<?php

namespace App\Livewire\BusinessOperator;

use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination;

class CompanyUserList extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $userTypeFilter = '';

    protected $queryString = ['search', 'sortField', 'sortDirection', 'userTypeFilter'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $users = User::where(function($query) {
            $query->where('user_type', 'CompanyAdmin')
                  ->orWhere('user_type', 'CompanyRepresentative');
        })
        ->when($this->userTypeFilter, function ($query) {
            return $query->where('user_type', $this->userTypeFilter);
        })
        ->where(function($query) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
        })
        ->with(['companyAdmin', 'companyRepresentative'])
        ->orderBy($this->sortField, $this->sortDirection)
        ->paginate(10);

        return view('livewire.business-operator.company-user-list', [
            'users' => $users,
        ]);
    }
}
