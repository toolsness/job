<?php

namespace App\Livewire\BusinessOperator;

use Livewire\Component;
use App\Models\Interview;
use App\Models\Company;
use App\Models\Vacancy;
use App\Models\Candidate;
use App\Models\InterviewSchedule;
use App\Models\InterviewMemo;
use App\Enum\InterviewStatus;
use App\Enum\ReservationStatus;
use Illuminate\Support\Facades\Auth;

class InterviewCreate extends Component
{
    public $company_id;
    public $vacancy_id;
    public $candidate_id;
    public $implementation_date;
    public $implementation_start_time;
    public $status;
    public $zoom_link;
    public $incharge_user_id;
    public $memoContent = '';
    public $companies;
    public $vacancies = [];
    public $inchargeUsers = [];

    protected $rules = [
        'company_id' => 'required|exists:companies,id',
        'vacancy_id' => 'required|exists:vacancies,id',
        'candidate_id' => 'required|exists:candidates,id',
        'implementation_date' => 'required|date',
        'implementation_start_time' => 'required|date_format:H:i',
        'status' => 'required|string',
        'zoom_link' => 'nullable|url',
        'incharge_user_id' => 'required|exists:users,id',
        'memoContent' => 'nullable|string|max:1000',
    ];

    public function mount()
    {
        $this->companies = Company::all();
    }

    public function updatedCompanyId($value)
    {
        $this->vacancies = Vacancy::where('company_id', $value)
            ->where('publish_category', 'Published')
            ->get();
        $this->vacancy_id = null;

        $company = Company::find($value);
        $this->inchargeUsers = $company->companyAdmins->merge($company->companyRepresentatives)
            ->map(function ($user) {
                return [
                    'id' => $user->user_id,
                    'name' => $user->user->name,
                    'type' => $user instanceof \App\Models\CompanyAdmin ? 'Admin' : 'Representative'
                ];
            });
    }

    public function createInterview()
    {
        $this->validate();

        $interviewSchedule = InterviewSchedule::create([
            'vacancy_id' => $this->vacancy_id,
            'interview_date' => $this->implementation_date,
            'interview_start_time' => $this->implementation_start_time,
            'reservation_status' => ReservationStatus::RESERVED,
            'created_by' => Auth::id(),
        ]);

        $interview = Interview::create([
            'vacancy_id' => $this->vacancy_id,
            'candidate_id' => $this->candidate_id,
            'interview_schedule_id' => $interviewSchedule->id,
            'implementation_date' => $this->implementation_date,
            'implementation_start_time' => $this->implementation_start_time,
            'status' => $this->status,
            'zoom_link' => $this->zoom_link,
            'incharge_user_id' => $this->incharge_user_id,
            'created_by' => Auth::id(),
        ]);

        if ($this->memoContent) {
            InterviewMemo::create([
                'interview_id' => $interview->id,
                'user_id' => Auth::id(),
                'content' => $this->memoContent,
            ]);
        }

        // session()->flash('message', 'Interview created successfully.');
        flash()->success('Interview created successfully.');
        return redirect()->route('business-operator.interviews.index');
    }

    public function render()
    {
        $candidates = Candidate::all();
        $statuses = InterviewStatus::cases();

        return view('livewire.business-operator.interview-create', [
            'candidates' => $candidates,
            'statuses' => $statuses,
        ]);
    }
}
