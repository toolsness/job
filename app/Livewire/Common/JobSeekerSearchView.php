<?php

namespace App\Livewire\Common;

use Livewire\Component;
use App\Models\Candidate;
use App\Models\Interview;
use App\Enum\InterviewStatus;
use App\Models\Vacancy;
use Illuminate\Support\Facades\Auth;

class JobSeekerSearchView extends Component
{
    public $candidate;
    public $search;
    public $page;
    public $isScoutedByCurrentUser = false;

    public function mount($id, $search = '', $page = 1)
    {
        $this->candidate = Candidate::with(['qualifications.qualificationCategory', 'desiredJobType', 'country'])->findOrFail($id);
        $this->search = $search;
        $this->page = $page;
        $this->isScoutedByCurrentUser = $this->checkIfScoutedByCurrentUser();
    }

    public function toggleScout()
    {
        $user = Auth::user();
        $companyId = $this->getUserCompanyId($user);

        if (!$companyId) {
            flash()->error('Unable to determine your company. Please contact support.');
            return;
        }

        $interview = $this->getScoutedInterview($companyId);

        if ($interview) {
            // Remove scout
            $interview->delete();
            $this->isScoutedByCurrentUser = false;
            flash()->success('Candidate has been unscout.');
        } else {
            // Redirect to job selection page
            return redirect()->route('job-seeker.select-job', ['candidateId' => $this->candidate->id], flash()->info('Select a job to scout.'));
        }
    }

    private function checkIfScoutedByCurrentUser()
    {
        $user = Auth::user();
        $companyId = $this->getUserCompanyId($user);

        if (!$companyId) {
            return false;
        }

        return $this->getScoutedInterview($companyId) !== null;
    }

    private function getScoutedInterview($companyId)
    {
        return Interview::where('candidate_id', $this->candidate->id)
            ->whereHas('vacancy', function ($query) use ($companyId) {
                $query->where(function ($q) use ($companyId) {
                    $q->whereHas('companyRepresentative', function ($r) use ($companyId) {
                        $r->where('company_id', $companyId);
                    })->orWhereHas('companyAdmin', function ($r) use ($companyId) {
                        $r->where('company_id', $companyId);
                    });
                });
            })
            ->where('status', InterviewStatus::SCOUTED)
            ->first();
    }

    private function getUserCompanyId($user)
    {
        switch ($user->user_type) {
            case 'CompanyRepresentative':
                return $user->companyRepresentative->company_id ?? null;
            case 'CompanyAdmin':
                return $user->companyAdmin->company_id ?? null;
            case 'BusinessOperator':
                // Adjust this logic if business operators can access multiple companies
                return $user->businessOperator->company_id ?? null;
            default:
                return null;
        }
    }

    public function render()
    {
        return view('livewire.common.job-seeker-search-view');
    }
}
