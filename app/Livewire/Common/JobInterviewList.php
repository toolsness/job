<?php

namespace App\Livewire\Common;

use App\Enum\InterviewStatus;
use App\Enum\ReservationStatus;
use App\Mail\InterviewNotificationCandidate;
use App\Mail\InterviewStatusChangeMail;
use App\Models\Interview;
use App\Models\InterviewSchedule;
use App\Models\InterviewTimeSlot;
use App\Models\Message;
use App\Models\MessageThread;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\CalendarLinks\Link;

class JobInterviewList extends Component
{
    use WithPagination;

    public $sortField = 'implementation_date';

    public $sortDirection = 'desc';

    public $vacancyId;

    public $filterStatus = '';

    public $filterDateFrom = '';

    public $filterDateTo = '';

    public $filterCompany = '';

    public $showFilters = false;

    public $showModal = false;

    public $modalType = '';

    public $selectedInterviewId;

    public $selectedInterview;

    public $reason = '';

    public $otherReason = '';

    public $showConfirmation = false;

    public $confirmationMessage = '';

    public $showScheduleModal = false;

    public $interviewDate;

    public $interviewTime;

    public $showArchived = false;

    public $showZoomLinkModal = false;

    public $editingZoomLink = false;

    public $zoomLink = '';

    public $tempZoomLink = '';

    protected $queryString = ['sortField', 'sortDirection'];

    public function mount($vacancyId = null)
    {
        $this->vacancyId = $vacancyId;

        if (Auth::user()->user_type === 'Student') {
            flash()->warning('You have to complete orientation and CV to access this page.');

            return redirect()->route('student.candidate-details');
        }
    }

    public function toggleFilters()
    {
        $this->showFilters = ! $this->showFilters;
    }

    public function applyFilters()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->filterStatus = '';
        $this->filterDateFrom = '';
        $this->filterDateTo = '';
        $this->filterCompany = '';
        $this->resetPage();
    }

    public function openModal($type, $interviewId)
    {
        $this->modalType = $type;
        $this->selectedInterviewId = $interviewId;
        $this->selectedInterview = Interview::with(['vacancy', 'candidate'])->find($interviewId);
        $this->showModal = true;
        $this->dispatch('popup-opened');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->modalType = '';
        $this->selectedInterviewId = null;
        $this->selectedInterview = null;
        $this->reason = '';
        $this->otherReason = '';
    }

    public function updatedReason($value)
    {
        if ($value !== 'Other (please specify)') {
            $this->otherReason = '';
        }
    }

    public function openZoomLinkModal($interviewId)
    {
        $this->selectedInterviewId = $interviewId;
        $interview = Interview::find($interviewId);
        if ($interview) {
            $this->tempZoomLink = $interview->zoom_link ?? '';
        }
        $this->showZoomLinkModal = true;
        $this->dispatch('popup-opened');
    }

    public function saveZoomLink()
    {
        $this->validate([
            'tempZoomLink' => 'required|url',
        ]);

        $interview = Interview::find($this->selectedInterviewId);
        if ($interview) {
            $interview->update([
                'zoom_link' => $this->tempZoomLink,
            ]);
            $this->closeZoomLinkModal();
            flash()->success('Zoom link saved successfully.');
        }
    }

    public function closeZoomLinkModal()
    {
        $this->showZoomLinkModal = false;
        $this->tempZoomLink = '';
        $this->selectedInterviewId = null;
    }

    public function cancelInterviewSlot()
    {
        $interview = Interview::find($this->selectedInterviewId);
        if ($interview) {
            $oldStatus = $interview->status;
            $interview->status = InterviewStatus::CANCELLATION_REFUSAL;
            $interview->reason = $this->reason === 'Other (please specify)' ? $this->otherReason : $this->reason;
            $interview->save();

            // Create a new slot
            $this->createSlotAfterCancellation($interview);

            // Send notification
            $this->sendStatusChangeNotification($interview, $oldStatus);

            $this->showConfirmation = true;
            $this->confirmationMessage = Auth::user()->user_type === 'Candidate'
                ? 'You have cancelled the interview.'
                : 'We have cancelled the interview with this job seeker.';
        }
        $this->closeModal();
        flash()->success('Cancelled the interview with this job seeker successfully.');
    }

    public function refuseInterviewSlot()
    {
        $interview = Interview::find($this->selectedInterviewId);
        if ($interview) {
            $oldStatus = $interview->status;
            $interview->status = InterviewStatus::CANCELLATION_REFUSAL;
            $interview->reason = $this->reason === 'Other (please specify)' ? $this->otherReason : $this->reason;
            $interview->save();

            // Create a new slot
            $this->createSlotAfterCancellation($interview);

            // Send notification
            $this->sendStatusChangeNotification($interview, $oldStatus);

            $this->showConfirmation = true;
            $this->confirmationMessage = 'We have refused to interview this job seeker.';
        }
        $this->closeModal();
        flash()->success('We have refused to interview this job seeker.');
    }

    private function createSlotAfterCancellation($interview)
    {
        InterviewTimeSlot::create([
            'company_id' => $interview->vacancy->company_id,
            'date' => $interview->implementation_date,
            'start_time' => $interview->implementation_start_time,
            'end_time' => Carbon::parse($interview->implementation_start_time)->addMinutes(60),
            'status' => 'available',
            'user_id' => $interview->incharge_user_id,
            'vacancy_id' => NULL,
        ]);
    }

    public function sendNotification($interviewId)
    {
        $interview = Interview::find($interviewId);
        if (! $interview || ! $interview->zoom_link) {
            flash()->error('Unable to send notification. Please ensure Zoom link is set.');

            return;
        }

        $inChargeUser = $interview->inchargeUser;
        $candidate = $interview->candidate;
        $company = $interview->vacancy->company;

        $from = Carbon::parse($interview->implementation_date->format('Y-m-d').' '.$interview->implementation_start_time->format('H:i:s'));
        $to = $from->copy()->addHour();
        $link = Link::create('Interview: '.$company->name.' - '.$candidate->name, $from, $to)
            ->description('Interview for '.$interview->vacancy->job_title)
            ->address($interview->zoom_link);

        $interview_link = url(route('interview.details', ['interview' => $interview->id]));

        $calendarLinks = [
            'google' => $link->google(),
            'ics' => $link->ics(),
            'outlook' => $link->webOutlook(),
        ];

        $this->createNotificationMessage($interview, $candidate, $company, $calendarLinks, $interview_link);

        Mail::to($candidate->student->user->email)->send(new InterviewNotificationCandidate($interview, $company, $candidate, $calendarLinks));

        flash()->success('Notification sent successfully.');
    }

    private function createNotificationMessage($interview, $candidate, $company, $calendarLinks, $interview_link)
    {
        $thread = MessageThread::create([
            'title' => 'Interview Notification: '.$interview->vacancy->job_title,
            'inquiry_type' => 'interview',
        ]);

        Message::create([
            'thread_id' => $thread->id,
            'sender_user_id' => Auth::id(),
            'sender_user_type' => Auth::user()->user_type,
            'receiver_user_id' => $candidate->student->user_id,
            'receiver_user_type' => 'Student',
            'sent_at' => now(),
            'content' => $this->generateNotificationMessageContent($interview, $company, $interview_link),
            'message_category' => 'Sent',
        ]);
    }

    private function generateNotificationMessageContent($interview, $company, $interview_link)
    {
        return "
            Interview Notification:
            Company: {$company->name}
            Job Title: {$interview->vacancy->job_title}
            Date: {$interview->implementation_date->format('Y-m-d')}
            Time: {$interview->implementation_start_time->format('H:i')}
            Zoom Link: {$interview->zoom_link}

            Please visit: {$interview_link} to view details.
        ";
    }

    private function getInChargeMessageContent($interview, $company, $candidate, $calendarLinks, $interview_link)
    {
        return "
            Interview Scheduled:
            Interview Id: {$interview->id}
            Candidate: {$candidate->name}
            Job Title: {$interview->vacancy->job_title}
            Date: {$interview->implementation_date->format('Y-m-d')}
            Time: {$interview->implementation_start_time->format('H:i')}
            Zoom Link: {$interview->zoom_link}

            Please visit: $interview_link to view details.
        ";
    }

    private function getCandidateMessageContent($interview, $company, $candidate, $calendarLinks, $interview_link)
    {
        return "
            Interview Notification:
            Interview Id: {$interview->id}
            Company: {$company->name}
            Job Title: {$interview->vacancy->job_title}
            Date: {$interview->implementation_date->format('Y-m-d')}
            Time: {$interview->implementation_start_time->format('H:i')}
            Zoom Link: {$interview->zoom_link}

            Please visit: $interview_link to view details.

        ";
    }

    public function getGoogleCalendarLink($interview)
    {
        return $this->getCalendarLink($interview)->google();
    }

    public function getAppleCalendarLink($interview)
    {
        return $this->getCalendarLink($interview)->ics();
    }

    public function getOutlookCalendarLink($interview)
    {
        return $this->getCalendarLink($interview)->webOutlook();
    }

    private function getCalendarLink($interview)
    {
        $from = Carbon::parse($interview->implementation_date->format('Y-m-d').' '.$interview->implementation_start_time->format('H:i:s'));
        $to = $from->copy()->addHour();

        return Link::create(
            'Interview: '.$interview->vacancy->job_title,
            $from,
            $to
        )
            ->description('Interview for '.$interview->vacancy->job_title)
            ->address($interview->zoom_link ?? 'No Zoom link provided');
    }

    private function updateInterviewStatus($status)
    {
        $interview = Interview::find($this->selectedInterviewId);
        if ($interview) {
            $oldStatus = $interview->status;
            $interview->status = $status;
            $interview->reason = $this->reason === 'Other (please specify)' ? $this->otherReason : $this->reason;

            // Update result based on status
            switch ($status) {
                case InterviewStatus::UNOFFICIAL_OFFER:
                    $interview->result = 'Passing';
                    $interview->result_notification_date = Carbon::now();
                    break;
                case InterviewStatus::EMPLOYMENT_APPLICATION:
                    $interview->result = 'Passing';
                    $interview->employment_contract_procedure_application_date = Carbon::now();
                    break;
                case InterviewStatus::HIRED:
                    $interview->result = 'Passing';
                    break;
                case InterviewStatus::INTERVIEW_UNSUCCESSFUL:
                    $interview->result = 'Failed';
                    $interview->result_notification_date = Carbon::now();
                    break;
                case InterviewStatus::OFFER_WITHDRAWN:
                case InterviewStatus::APPLICATION_WITHDRAWN:
                case InterviewStatus::OFFER_DECLINED:
                case InterviewStatus::CANCELLATION_REFUSAL:
                case InterviewStatus::INTERVIEW_CANCELLED:
                case InterviewStatus::OFFER_DECLINED_BY_CANDIDATE:
                    $interview->result = 'Cancelled';
                    break;
                case InterviewStatus::INTERVIEW_CONFIRMED:
                    break;
                case InterviewStatus::OFFER_ACCEPTED:
                    break;
                case InterviewStatus::SCOUTED:
                    break;
                case InterviewStatus::INTERVIEW_CONDUCTED:
                    break;
                case InterviewStatus::APPLICATION_FROM_STUDENTS:
                default:
                    $interview->result = 'NotApplicable';
            }

            $interview->save();

            // Send notification
            $this->sendStatusChangeNotification($interview, $oldStatus);
        }
        $this->closeModal();
    }

    private function sendStatusChangeNotification($interview, $oldStatus)
    {
        $user = Auth::user();
        $isCandidate = $user->user_type === 'Candidate';

        $recipient = $isCandidate ? $interview->inchargeUser : $interview->candidate->student->user;
        $subject = 'Interview Status Update: '.$interview->vacancy->job_title;

        $details = [
            'interview_id' => $interview->id,
            'job_title' => $interview->vacancy->job_title,
            'company_name' => $interview->vacancy->company->name,
            'candidate_name' => $interview->candidate->name,
            'candidate_country' => $interview->candidate->country->country_name,
            'candidate_education' => $interview->candidate->last_education,
            'old_status' => $oldStatus,
            'new_status' => $interview->status,
            'reason' => $interview->reason,
        ];

        // Send email
        Mail::to($recipient->email)->send(new InterviewStatusChangeMail($subject, $details));

        // Create message thread and message
        $thread = MessageThread::create([
            'title' => $subject,
            'inquiry_type' => 'interview',
        ]);

        Message::create([
            'thread_id' => $thread->id,
            'sender_user_id' => $user->id,
            'sender_user_type' => $user->user_type,
            'receiver_user_id' => $recipient->id,
            'receiver_user_type' => $recipient->user_type,
            'sent_at' => now(),
            'content' => $this->generateMessageContent($details),
            'message_category' => 'Sent',
        ]);
    }

    private function generateMessageContent($details)
    {
        $content = "Interview status has been updated.\n\n";
        $content .= "Job Title: {$details['job_title']}\n";
        $content .= "Company: {$details['company_name']}\n";
        $content .= "Candidate: {$details['candidate_name']}\n";
        $content .= "Country: {$details['candidate_country']}\n";
        $content .= "Last Education: {$details['candidate_education']}\n\n";
        $content .= "Status changed from {$details['old_status']->value} to {$details['new_status']->value}.\n";
        if ($details['reason']) {
            $content .= "Reason: {$details['reason']}\n";
        }
        $content .= "\nFor more details, please visit: ".route('interview.details', ['interview' => $details['interview_id']]);

        return $content;
    }

    public function confirmEmploymentApplication()
    {
        $this->updateInterviewStatus(InterviewStatus::EMPLOYMENT_APPLICATION);
        $this->showConfirmation = true;
        $this->confirmationMessage = 'Thank you for your employment application. We will proceed with the employment application with this job seeker. Our office will contact you within one business day.';
    }

    public function unofficialOffer()
    {
        $this->updateInterviewStatus(InterviewStatus::UNOFFICIAL_OFFER);
        $this->showConfirmation = true;
        $this->confirmationMessage = 'An unofficial offer has been made to this job seeker.';

    }

    public function withdrawOffer()
    {
        $this->updateInterviewStatus(InterviewStatus::OFFER_WITHDRAWN);
        $this->showConfirmation = true;
        $this->confirmationMessage = 'We have withdrawn the job offer for this job seeker.';
    }

    public function cancelInterview()
    {
        $this->updateInterviewStatus(InterviewStatus::CANCELLATION_REFUSAL);
        $this->showConfirmation = true;
        $this->confirmationMessage = Auth::user()->user_type === 'Candidate'
            ? 'You have cancelled the interview.'
            : 'We have cancelled the interview with this job seeker.';
    }

    public function refuseInterview()
    {
        $this->updateInterviewStatus(InterviewStatus::CANCELLATION_REFUSAL);

        $this->showConfirmation = true;
        $this->confirmationMessage = 'We have refused to interview this job seeker.';
    }



    public function markInterviewUnsuccessful()
    {
        $this->updateInterviewStatus(InterviewStatus::INTERVIEW_UNSUCCESSFUL);
        $this->showConfirmation = true;

        $this->confirmationMessage = 'The interview has been marked as unsuccessful.';
    }

    public function makeUnofficialOffer()
    {
        $this->updateInterviewStatus(InterviewStatus::UNOFFICIAL_OFFER);
        $this->showConfirmation = true;
        $this->confirmationMessage = 'An unofficial offer has been made to this job seeker.';
    }

    public function employmentApplication($interviewId = null)
    {
        $interview = Interview::findOrFail($interviewId);
        $this->updateInterviewStatus(InterviewStatus::EMPLOYMENT_APPLICATION);
        $this->showConfirmation = true;
        if (Auth::user()->user_type === 'Candidate') {
            $this->confirmationMessage = 'Thank you for your employment application. We will proceed with the employment application with this job seeker. Our office will contact you within one business day.';
        } else {
            $this->confirmationMessage = 'Congratulations! We will now proceed with your employment application with this company. Please wait for our office to contact you within one business day.';
        }
        $this->closeModal();

        return redirect()->route('student.interview.confirmation', ['interview' => $interviewId]);
    }

    public function declineOffer()
    {
        $this->updateInterviewStatus(InterviewStatus::OFFER_DECLINED);
        $this->showConfirmation = true;
        $this->confirmationMessage = 'You have declined the job offer.';
    }

    public function closeConfirmation()
    {
        $this->showConfirmation = false;
        $this->confirmationMessage = '';
        $this->reason = '';
        $this->otherReason = '';
    }

    public function saveInterviewSchedule()
    {
        $interview = Interview::find($this->selectedInterviewId);
        if ($interview) {
            $interviewSchedule = $interview->interviewSchedule ?? new InterviewSchedule;
            $interviewSchedule->vacancy_id = $interview->vacancy_id;
            $interviewSchedule->interview_date = $this->interviewDate;
            $interviewSchedule->interview_start_time = $this->interviewTime;
            $interviewSchedule->reservation_status = ReservationStatus::RESERVED;
            $interviewSchedule->save();

            $interview->interviewSchedule()->associate($interviewSchedule);
            $interview->status = InterviewStatus::INTERVIEW_CONFIRMED;
            $interview->booking_confirmation_date = Carbon::now();
            $interview->save();

            $this->showScheduleModal = false;
            $this->showConfirmation = true;
            $this->confirmationMessage = "Interview schedule has been finalized and the status is set to 'Interview confirmed'.";

            $this->reset(['interviewDate', 'interviewTime']);
        }
    }

    private function refreshInterviews()
    {
        $user = Auth::user();
        $interviewsQuery = Interview::with(['vacancy.companyRepresentative.company', 'vacancy.companyAdmin.company', 'student', 'candidate', 'inchargeUser', 'interviewSchedule'])
            ->orderBy($this->sortField, $this->sortDirection);

        $companyId = $this->getUserCompanyId($user);

        if ($companyId) {
            if ($user->user_type === 'Candidate') {
                $interviewsQuery->where('candidate_id', $companyId);
            } else {
                $interviewsQuery->whereHas('vacancy', function ($query) use ($companyId) {
                    $query->where(function ($q) use ($companyId) {
                        $q->whereHas('companyRepresentative', function ($r) use ($companyId) {
                            $r->where('company_id', $companyId);
                        })->orWhereHas('companyAdmin', function ($r) use ($companyId) {
                            $r->where('company_id', $companyId);
                        });
                    });
                });
            }
        }

        if ($this->vacancyId) {
            $interviewsQuery->where('vacancy_id', $this->vacancyId);
        }

        if ($this->filterStatus) {
            $interviewsQuery->where('status', $this->filterStatus);
        }

        if ($this->filterDateFrom) {
            $interviewsQuery->whereDate('implementation_date', '>=', $this->filterDateFrom);
        }

        if ($this->filterDateTo) {
            $interviewsQuery->whereDate('implementation_date', '<=', $this->filterDateTo);
        }

        if ($this->filterCompany) {
            $interviewsQuery->whereHas('vacancy', function ($query) {
                $query->whereHas('companyRepresentative.company', function ($q) {
                    $q->where('name', 'like', '%'.$this->filterCompany.'%');
                })->orWhereHas('companyAdmin.company', function ($q) {
                    $q->where('name', 'like', '%'.$this->filterCompany.'%');
                });
            });
        }

        if ($this->showArchived) {
            $interviewsQuery->where('status', InterviewStatus::ARCHIVED);
        } else {
            $interviewsQuery->where('status', '!=', InterviewStatus::ARCHIVED);
        }

        return $interviewsQuery->paginate(5);
    }

    private function getUserCompanyId($user)
    {
        switch ($user->user_type) {
            case 'CompanyRepresentative':
                return $user->companyRepresentative->company_id ?? null;
            case 'CompanyAdmin':
                return $user->companyAdmin->company_id ?? null;
            case 'BusinessOperator':
                return $user->businessOperator->company_id ?? null;
            case 'Candidate':
                $student = $user->student;
                if ($student) {
                    return $student->candidate->id ?? null;
                }

                return null;
            default:
                return null;
        }
    }

    public function archiveInterview($interviewId)
    {
        $interview = Interview::find($interviewId);
        if ($interview) {
            $interview->status = InterviewStatus::ARCHIVED;
            $interview->save();
        }
        $this->closeModal();
    }

    public function deleteInterview($interviewId)
    {
        $interview = Interview::find($interviewId);
        if ($interview && $interview->status === InterviewStatus::ARCHIVED) {
            $interview->delete();
        }
        $this->closeModal();
    }

    public function toggleShowArchived()
    {
        $this->showArchived = ! $this->showArchived;
        $this->resetPage();
    }

    public function unsuccessfulInterview()
    {
        $this->updateInterviewStatus(InterviewStatus::INTERVIEW_UNSUCCESSFUL);
        $this->showConfirmation = true;
        $this->confirmationMessage = 'The interview has been marked as unsuccessful.';
    }

    public function hire($interviewId)
    {
        $interview = Interview::findOrFail($interviewId);
        if ($interview->status === InterviewStatus::EMPLOYMENT_APPLICATION) {

            $this->updateInterviewStatus(InterviewStatus::HIRED);
            $interview->save();
            $this->showConfirmation = true;
            $this->confirmationMessage = 'The candidate has been successfully hired.';
        }
    }

    public function approveScout($interviewId)
    {
        $interview = Interview::findOrFail($interviewId);
        if ($interview->status === InterviewStatus::SCOUTED) {

            $this->closeModal();

            return redirect()->route('student.candidate-details', ['vacancyId' => $interview->vacancy_id, 'interviewId' => $interviewId]);
        }
    }

    public function declineScout($interviewId)
    {
        $interview = Interview::findOrFail($interviewId);
        if ($interview->status === InterviewStatus::SCOUTED) {
            $this->updateInterviewStatus(InterviewStatus::OFFER_DECLINED);
            $interview->reason = $this->reason === 'Other (please specify)' ? $this->otherReason : $this->reason;

            $interview->save();
            $this->closeModal();
            $this->showConfirmation = true;
            $this->confirmationMessage = 'You have declined the scouting request.';
        }
    }

    public function cancelScout($interviewId)
    {
        $interview = Interview::findOrFail($interviewId);
        if ($interview->status === InterviewStatus::SCOUTED) {
            $this->updateInterviewStatus(InterviewStatus::CANCELLATION_REFUSAL);
            $interview->reason = $this->reason === 'Other (please specify)' ? $this->otherReason : $this->reason;
            $interview->save();
            $this->closeModal();
            $this->showConfirmation = true;
            $this->confirmationMessage = 'You have cancelled the scouting request.';
        }
    }

    public function finalizeInterviewSchedule($interviewId)
    {
        $interview = Interview::findOrFail($interviewId);
        if ($interview->status === InterviewStatus::APPLICATION_FROM_STUDENTS) {
            $interview->status = InterviewStatus::INTERVIEW_CONFIRMED;
            $this->updateInterviewStatus(InterviewStatus::INTERVIEW_CONFIRMED);

            $interview->booking_confirmation_date = Carbon::now();
            $interview->save();

            InterviewSchedule::create([
                'vacancy_id' => $interview->vacancy_id,
                'interview_date' => $interview->implementation_date,
                'interview_start_time' => $interview->implementation_start_time,
                'reservation_status' => ReservationStatus::RESERVED,
            ]);

            $this->showConfirmation = true;
            $this->confirmationMessage = 'Interview schedule has been finalized.';

            // Send email and message to candidate
            $this->sendInterviewConfirmationNotification($interview);
        }
    }

    private function sendInterviewConfirmationNotification($interview)
    {
        $candidate = $interview->candidate;
        $company = $interview->vacancy->company;

        $from = Carbon::parse($interview->implementation_date->format('Y-m-d').' '.$interview->implementation_start_time->format('H:i:s'));
        $to = $from->copy()->addHour();
        $link = Link::create('Interview: '.$company->name.' - '.$candidate->name, $from, $to)
            ->description('Interview for '.$interview->vacancy->job_title)
            ->address($interview->zoom_link ?? 'No Zoom link provided yet, please check your mail later');
        $interview_link = url(route('interview.details', ['interview' => $interview->id]));

        $calendarLinks = [
            'google' => $link->google(),
            'ics' => $link->ics(),
            'outlook' => $link->webOutlook(),
        ];

        // Send email
        Mail::to($candidate->student->user->email)->send(new InterviewNotificationCandidate($interview, $company, $candidate, $calendarLinks));

        // Create message thread and message
        $thread = MessageThread::create([
            'title' => 'Interview Confirmed: '.$interview->vacancy->job_title,
            'inquiry_type' => 'interview',
        ]);

        Message::create([
            'thread_id' => $thread->id,
            'sender_user_id' => Auth::id(),
            'sender_user_type' => Auth::user()->user_type,
            'receiver_user_id' => $candidate->student->user->id,
            'receiver_user_type' => 'Student',
            'sent_at' => now(),
            'content' => $this->getInterviewConfirmationMessageContent($interview, $company, $calendarLinks, $interview_link),
            'message_category' => 'Sent',
        ]);
    }

    private function getInterviewConfirmationMessageContent($interview, $company, $calendarLinks, $interview_link)
    {
        return "
            Your interview has been confirmed:
            Company: {$company->name}
            Job Title: {$interview->vacancy->job_title}
            Date: {$interview->implementation_date->format('Y-m-d')}
            Time: {$interview->implementation_start_time->format('H:i')}
            ".($interview->zoom_link ? "Zoom Link: {$interview->zoom_link}" : "Zoom link not provided yet. You will be notified when it's available.")."

            To view more details and add the interview to your calendar, please visit: {$interview_link}

            Thank you for using our platform. Please be prepared and punctual for your interview.
            If you need to reschedule or have any questions, please contact us through the messaging system.

            Best of luck with your interview!
        ";
    }

    public function render()
    {
        $user = Auth::user();
        $interviews = $this->refreshInterviews();
        $statuses = array_filter(InterviewStatus::cases(), function ($status) {
            return $status !== InterviewStatus::ARCHIVED;
        });

        return view('livewire.common.job-interview-list', [
            'interviews' => $interviews,
            'interviewDetailsRoute' => 'interview.details',
            'statuses' => $statuses,
        ]);
    }
}
