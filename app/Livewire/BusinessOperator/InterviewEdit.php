<?php

namespace App\Livewire\BusinessOperator;

use App\Enum\InterviewStatus;
use App\Enum\ReservationStatus;
use App\Mail\InterviewNotificationCandidate;
use App\Mail\InterviewNotificationInCharge;
use App\Models\Candidate;
use App\Models\Company;
use App\Models\Interview;
use App\Models\InterviewMemo;
use App\Models\InterviewSchedule;
use App\Models\Message;
use App\Models\User;
use App\Models\Vacancy;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Spatie\CalendarLinks\Link;

class InterviewEdit extends Component
{
    public Interview $interview;
    public $company_id;
    public $vacancy_id;
    public $candidate_id;
    public $implementation_date;
    public $implementation_start_time;
    public $status;
    public $zoom_link;
    public $incharge_user_id;
    public $isEditing = false;
    public $showDeleteConfirmation = false;
    public $memoContent = '';
    public $memos = [];
    public $companies;
    public $vacancies = [];
    public $inchargeUsers = [];
    public $result;
    public $employment_contract_procedure_application_date;
    public $booking_request_date_student;
    public $booking_request_date_company;
    public $booking_confirmation_date;
    public $result_notification_date;
    public $showZoomLinkModal = false;
    public $tempZoomLink = '';

    public function rules()
    {
        return [
            'company_id' => 'required|exists:companies,id',
            'vacancy_id' => 'required|exists:vacancies,id',
            'candidate_id' => 'required|exists:candidates,id',
            'implementation_date' => 'required|date',
            'implementation_start_time' => 'required|date_format:H:i',
            'status' => 'required|string|in:' . implode(',', array_column(InterviewStatus::cases(), 'value')),
            'zoom_link' => 'nullable|url',
            'incharge_user_id' => 'required|exists:users,id',
            'memoContent' => 'nullable|string|max:1000',
            'result' => 'nullable|string',
        ];
    }

    public function mount(Interview $interview)
    {
        $this->interview = $interview;
        $this->companies = Company::all();
        $this->loadInterviewData();
        $this->memos = $interview->memos->sortByDesc('created_at');
    }

    public function loadInterviewData()
    {
        $this->company_id = $this->interview->vacancy->company_id;
        $this->vacancy_id = $this->interview->vacancy_id;
        $this->candidate_id = $this->interview->candidate_id;
        $this->implementation_date = $this->interview->implementation_date?->format('Y-m-d') ?? Carbon::now()->format('Y-m-d');
        $this->implementation_start_time = $this->interview->implementation_start_time?->format('H:i') ?? Carbon::now()->format('H:i');
        $this->status = $this->interview->status->value;
        $this->zoom_link = $this->interview->zoom_link;
        $this->incharge_user_id = $this->interview->incharge_user_id;
        $this->result = $this->interview->result;
        $this->employment_contract_procedure_application_date = $this->interview->employment_contract_procedure_application_date?->format('Y-m-d');
        $this->booking_request_date_student = $this->interview->booking_request_date_student?->format('Y-m-d');
        $this->booking_request_date_company = $this->interview->booking_request_date_company?->format('Y-m-d');
        $this->booking_confirmation_date = $this->interview->booking_confirmation_date?->format('Y-m-d');
        $this->result_notification_date = $this->interview->result_notification_date?->format('Y-m-d');

        $this->vacancies = Vacancy::where('company_id', $this->company_id)->get();
        $this->updateInchargeUsers();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, $this->rules());
    }

    public function saveZoomLink()
    {
        $this->validate([
            'tempZoomLink' => 'required|url',
        ]);

        $this->interview->update([
            'zoom_link' => $this->tempZoomLink,
        ]);

        $this->zoom_link = $this->tempZoomLink;
        $this->showZoomLinkModal = false;
        $this->tempZoomLink = '';

        flash()->success('Zoom link saved successfully.');
    }

    public function performNotificationSend()
    {
        if (!$this->interview->zoom_link) {
            $this->showZoomLinkModal = true;
            return;
        }

        $this->sendNotification();
    }

    public function updatedCompanyId($value)
    {
        $this->vacancies = Vacancy::where('company_id', $value)->get();

        if (!$this->vacancies->contains('id', $this->vacancy_id)) {
            $this->vacancy_id = null;
        }

        $this->updateInchargeUsers();
    }

    public function updateInchargeUsers()
    {
        $company = Company::find($this->company_id);
        if ($company) {
            $this->inchargeUsers = $company->companyAdmins->merge($company->companyRepresentatives)
                ->map(function ($user) {
                    return [
                        'id' => $user->user_id,
                        'name' => $user->user->name,
                        'type' => $user instanceof \App\Models\CompanyAdmin ? 'Admin' : 'Representative',
                    ];
                })->toArray();
        } else {
            $this->inchargeUsers = [];
        }
    }

    public function startEditing()
    {
        $this->isEditing = true;
    }

    public function cancelEditing()
    {
        $this->isEditing = false;
        $this->loadInterviewData();
    }

    public function updateInterview()
    {
        $this->validate($this->rules());

        $implementationDateTime = Carbon::parse($this->implementation_date . ' ' . $this->implementation_start_time);

        $this->interview->update([
            'vacancy_id' => $this->vacancy_id,
            'candidate_id' => $this->candidate_id,
            'implementation_date' => $this->implementation_date,
            'implementation_start_time' => $implementationDateTime,
            'status' => InterviewStatus::from($this->status),
            'zoom_link' => $this->zoom_link,
            'incharge_user_id' => $this->incharge_user_id,
            'result' => $this->result,
            'updated_by' => Auth::id(),
        ]);

        InterviewSchedule::updateOrCreate(
            [
                'vacancy_id' => $this->vacancy_id,
                'interview_date' => $this->implementation_date,
            ],
            [
                'interview_start_time' => $implementationDateTime,
                'reservation_status' => ReservationStatus::RESERVED,
                'updated_by' => Auth::id(),
            ]
        );

        if ($this->memoContent) {
            InterviewMemo::create([
                'interview_id' => $this->interview->id,
                'user_id' => Auth::id(),
                'content' => $this->memoContent,
            ]);
            $this->memoContent = '';
            $this->interview->refresh();
            $this->memos = $this->interview->memos->sortByDesc('created_at');
        }

        $this->isEditing = false;
        flash()->success('Interview updated successfully.');
    }

    public function saveMemo()
    {
        $this->validate(['memoContent' => 'required|string|max:1000']);

        InterviewMemo::create([
            'interview_id' => $this->interview->id,
            'user_id' => Auth::id(),
            'content' => $this->memoContent,
        ]);

        $this->memoContent = '';
        $this->interview->refresh();
        $this->memos = $this->interview->memos->sortByDesc('created_at');
    }

    public function confirmDelete()
    {
        $this->showDeleteConfirmation = true;
    }

    public function cancelDelete()
    {
        $this->showDeleteConfirmation = false;
    }

    public function deleteInterview()
    {
        $this->interview->delete();
        flash()->success('Interview deleted successfully.');
        return redirect()->route('business-operator.interviews.index');
    }

    public function sendNotification()
    {
        $interview = $this->interview;
        $inChargeUser = $interview->inchargeUser;
        $candidate = $interview->candidate;
        $company = $interview->vacancy->company;

        $from = \DateTime::createFromFormat('Y-m-d H:i', $interview->implementation_date->format('Y-m-d') . ' ' . $interview->implementation_start_time->format('H:i'));
        $to = (clone $from)->modify('+1 hour');
        $link = Link::create('Interview: ' . $company->name . ' - ' . $candidate->name, $from, $to)
            ->description('Interview for ' . $interview->vacancy->job_title)
            ->address($interview->zoom_link);

        $calendarLinks = [
            'google' => $link->google(),
            'ics' => $link->ics(),
            'outlook' => $link->webOutlook(),
        ];

        Message::create([
            'sender_user_id' => Auth::id(),
            'sender_user_type' => Auth::user()->user_type,
            'receiver_user_id' => $inChargeUser->id,
            'receiver_user_type' => $inChargeUser->user_type,
            'sent_at' => now(),
            'title' => 'Interview Scheduled: ' . $interview->vacancy->job_title,
            'content' => $this->getInChargeMessageContent($interview, $company, $candidate, $calendarLinks),
            'message_category' => 'Sent',
            'inquiry_type' => 'Interview',
        ]);

        Message::create([
            'sender_user_id' => Auth::id(),
            'sender_user_type' => Auth::user()->user_type,
            'receiver_user_id' => $candidate->student->user->id,
            'receiver_user_type' => 'Student',
            'sent_at' => now(),
            'title' => 'Interview Notification: ' . $interview->vacancy->job_title,
            'content' => $this->getCandidateMessageContent($interview, $company, $candidate, $calendarLinks),
            'message_category' => 'Sent',
            'inquiry_type' => 'Interview',
        ]);

        Mail::to($inChargeUser->email)->send(new InterviewNotificationInCharge($interview, $company, $candidate, $calendarLinks));
        Mail::to($candidate->student->user->email)->send(new InterviewNotificationCandidate($interview, $company, $candidate, $calendarLinks));

        flash()->success('Notification sent successfully.');
        $this->showZoomLinkModal = false;
    }

    public function cancelZoomLinkSave()
    {
        $this->showZoomLinkModal = false;
        $this->tempZoomLink = '';
    }

    private function getInChargeMessageContent($interview, $company, $candidate, $calendarLinks)
    {
        return "
            Interview Scheduled:
            Interview Id: {$interview->id}
            Candidate: {$candidate->name}
            Job Title: {$interview->vacancy->job_title}
            Date: {$interview->implementation_date->format('Y-m-d')}
            Time: {$interview->implementation_start_time->format('H:i')}
            Zoom Link: {$interview->zoom_link}

        ";
    }

    private function getCandidateMessageContent($interview, $company, $candidate, $calendarLinks)
    {
        return "
            Interview Notification:
            Interview Id: {$interview->id}
            Company: {$company->name}
            Job Title: {$interview->vacancy->job_title}
            Date: {$interview->implementation_date->format('Y-m-d')}
            Time: {$interview->implementation_start_time->format('H:i')}
            Zoom Link: {$interview->zoom_link}

        ";
    }

    public function render()
    {
        $candidates = Candidate::all();
        $statuses = collect(InterviewStatus::cases())->map(function ($status) {
            return [
                'value' => $status->value,
                'label' => $status->getDisplayText(),
            ];
        });
        $results = ['Pending', 'Passing', 'Failed', 'Cancelled', 'NotApplicable'];

        return view('livewire.business-operator.interview-edit', [
            'candidates' => $candidates,
            'statuses' => $statuses,
            'results' => $results,
        ]);
    }
}
