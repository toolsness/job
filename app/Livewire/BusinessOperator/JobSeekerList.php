<?php

namespace App\Livewire\BusinessOperator;

use Livewire\Component;
use App\Models\Candidate;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class JobSeekerList extends Component
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
        $jobSeekers = Candidate::query()
            ->with(['student.user', 'country', 'qualifications', 'desiredJobType'])
            ->where(function ($query) {
                $query->whereHas('student.user', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                })
                ->orWhere('name', 'like', '%' . $this->search . '%')
                ->orWhere('last_education', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);

        return view('livewire.business-operator.job-seeker-list', [
            'jobSeekers' => $jobSeekers,
        ]);
    }
}
