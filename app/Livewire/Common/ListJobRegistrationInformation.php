<?php

namespace App\Livewire\Common;

use App\Models\Vacancy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination; // Storage
use Illuminate\Support\Facades\Gate;


class ListJobRegistrationInformation extends Component
{
    use WithPagination;

    public $search = '';

    public $userType;

    public $perPage = 10; // Number of items per page

    public function mount()
    {
        if (!Gate::allows('view-job-list-search')) {
            abort(403, 'Unauthorized action.');
        }

        $this->userType = Auth::user()->user_type;
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function loadJobListings()
    {
        $user = Auth::user();
        Log::info('Current User ID: '.$user->id);
        Log::info('User Type: '.$this->userType);

        $query = Vacancy::with(['companyRepresentative.company', 'companyAdmin.company', 'vacancyCategory']);

        if ($this->userType === 'CompanyAdmin') {
            $companyId = $user->companyAdmin->company_id;
            $query->where(function ($q) use ($companyId) {
                $q->whereHas('companyAdmin', function ($q) use ($companyId) {
                    $q->where('company_id', $companyId);
                })->orWhereHas('companyRepresentative', function ($q) use ($companyId) {
                    $q->where('company_id', $companyId);
                });
            });
        } elseif ($this->userType === 'CompanyRepresentative') {
            $companyId = $user->companyRepresentative->company_id;
            $query->where(function ($q) use ($companyId) {
                $q->whereHas('companyRepresentative', function ($q) use ($companyId) {
                    $q->where('company_id', $companyId);
                })->orWhereHas('companyAdmin', function ($q) use ($companyId) {
                    $q->where('company_id', $companyId);
                });
            });
        } elseif (in_array($this->userType, ['Student', 'Candidate'])) {
            $query->where('publish_category', 'Published');
        } else {
            Log::error('Invalid user type for job listing: '.$this->userType);

            return collect();
        }

        if (! empty($this->search)) {
            $query->where(function ($q) {
                $q->where('job_title', 'like', '%'.$this->search.'%');
            });
        }

        return $query->paginate($this->perPage);
    }

    public function render()
    {
        $vacancies = $this->loadJobListings();

        $jobListings = $vacancies->map(function ($vacancy) {
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
                'companyName' => $company->name ?? 'N/A',
            ];
        });

        return view('livewire.common.list-job-registration-information', [
            'jobListings' => $jobListings,
            'vacancies' => $vacancies,
        ]);
    }
}
