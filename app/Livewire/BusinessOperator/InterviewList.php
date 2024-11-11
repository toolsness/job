<?php

namespace App\Livewire\BusinessOperator;

use App\Models\Interview;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class InterviewList extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'implementation_date';
    public $sortDirection = 'desc';
    public $filterStatus = '';

    protected $queryString = ['sortField', 'sortDirection'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function render()
    {
        $interviews = Interview::with([
            'vacancy',
            'candidate',
            'interviewSchedule',
            'inchargeUser',
            'inchargeUser.companyAdmin',
            'inchargeUser.companyRepresentative'
        ])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('vacancy', function ($q) {
                        $q->where('job_title', 'like', '%'.$this->search.'%');
                    })
                        ->orWhereHas('candidate', function ($q) {
                            $q->where('name', 'like', '%'.$this->search.'%');
                        });
                });
            })
            ->when($this->filterStatus, function ($query) {
                $query->where('status', $this->filterStatus);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.business-operator.interview-list', [
            'interviews' => $interviews,
        ]);
    }
}
