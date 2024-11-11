<?php

namespace App\Livewire\Common;

use App\Models\Vacancy;
use App\Models\Candidate;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Facades\Gate;

class SelectJobForScouting extends Component
{
    public $candidateId;
    public $search = '';
    public $jobListings = [];

    public function mount($candidateId)
{
    $this->candidateId = $candidateId;
    $candidate = Candidate::findOrFail($candidateId);

    if (!Gate::allows('scout')) {
        abort(403, 'Unauthorized action.');
    }

    $this->loadJobListings();
}

    public function loadJobListings()
    {
        $user = Auth::user();
        $query = Vacancy::with(['companyAdmin.company', 'companyRepresentative.company', 'vacancyCategory']);

        if ($user->user_type === 'CompanyAdmin') {
            $companyId = $user->companyAdmin->company_id;
            $query->where(function ($q) use ($companyId) {
                $q->whereHas('companyAdmin', function ($q) use ($companyId) {
                    $q->where('company_id', $companyId);
                })->orWhereHas('companyRepresentative', function ($q) use ($companyId) {
                    $q->where('company_id', $companyId);
                });
            });
        } elseif ($user->user_type === 'CompanyRepresentative') {
            $companyId = $user->companyRepresentative->company_id;
            $query->where(function ($q) use ($companyId) {
                $q->whereHas('companyRepresentative', function ($q) use ($companyId) {
                    $q->where('company_id', $companyId);
                })->orWhereHas('companyAdmin', function ($q) use ($companyId) {
                    $q->where('company_id', $companyId);
                });
            });
        } else {
            // Handle other user types or throw an exception
            throw new \Exception('Invalid user type for job listing');
        }

        if (! empty($this->search)) {
            $query->where(function ($q) {
                $q->where('job_title', 'like', '%'.$this->search.'%')
                    ->orWhere('job_title', 'like', '%'.$this->search.'%');
            });
        }

        $this->jobListings = $query->get()->map(function ($vacancy) {
            $company = $vacancy->companyAdmin ? $vacancy->companyAdmin->company : $vacancy->companyRepresentative->company;

            return [
                'id' => $vacancy->id,
                'image' => $vacancy->image,
                'category' => $vacancy->vacancyCategory->name ?? 'N/A',
                'businessType' => $vacancy->job_title,
                'jobTitle' => $vacancy->job_title,
                'offerNumber' => $vacancy->id,
                'shopName' => $company->name ?? 'N/A',
                'salaryInfo' => $vacancy->monthly_salary,
                'shopAddress' => $vacancy->work_location,
                'japaneseLevel' => $vacancy->japanese_language,
            ];
        })->toArray();
    }

    public function updatedSearch()
    {
        $this->loadJobListings();
    }

    public function selectJob($jobId)
{
    $vacancy = Vacancy::findOrFail($jobId);
    if (!Gate::allows('scoutWith', $vacancy)) {
        abort(403, 'Unauthorized action.');
    }

    return redirect()->route('job-seeker.confirm-scouting', ['candidateId' => $this->candidateId, 'jobId' => $jobId], flash()->info('Please Confirm Scouting'));
}

    public function render()
    {
        return view('livewire.common.select-job-for-scouting');
    }
}
