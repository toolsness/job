<?php

namespace App\Livewire\Student;

use App\Enum\InterviewStatus;
use App\Mail\InterviewStatusChangeMail;
use App\Models\Interview;
use App\Models\InterviewTimeSlot;
use App\Models\Message;
use App\Models\MessageThread;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class InterviewConfirmation extends Component
{
    use AuthorizesRequests;

    public $interview;

    public $confirmationMessage;

    public $showCancelModal = false;

    public $cancelReason = '';

    protected $rules = [
        'cancelReason' => 'required|string|min:5',
    ];

    public function mount(Interview $interview)
    {
        $this->authorize('viewConfirmation', $interview);
        $this->interview = $interview->load(['vacancy.companyRepresentative.company', 'inchargeUser']);
        $this->updateConfirmationMessage();
    }

    public function updateConfirmationMessage()
    {
        if ($this->interview->status === InterviewStatus::APPLICATION_FROM_STUDENTS) {
            $this->confirmationMessage = 'Your application has been submitted. Please wait for the company to confirm the interview schedule.';
        } elseif ($this->interview->status === InterviewStatus::INTERVIEW_CONFIRMED) {
            $this->confirmationMessage = 'Your interview has been confirmed. Please make note of the date and time.';
        } elseif ($this->interview->status === InterviewStatus::CANCELLATION_REFUSAL) {
            $this->confirmationMessage = 'Your interview has been cancelled.';
        } elseif ($this->interview->status === InterviewStatus::INTERVIEW_CANCELLED) {
            $this->confirmationMessage = 'Your interview has been cancelled.';
        } elseif ($this->interview->status === InterviewStatus::INTERVIEW_CONDUCTED) {
            $this->confirmationMessage = 'Your interview has been conducted. Please wait for the result.';
        } elseif ($this->interview->status === InterviewStatus::EMPLOYMENT_APPLICATION) {
            $this->confirmationMessage = 'Thank you for your employment application. We will proceed with the employment application with this job seeker. Our office will contact you within one business day.';
        } elseif ($this->interview->status === InterviewStatus::HIRED) {
            $this->confirmationMessage = 'Your application has been accepted. You have been hired by this company.';
        }
    }

    public function openCancelModal()
    {
        $this->showCancelModal = true;
    }

    public function cancelInterview()
    {
        $this->validate();

        $oldStatus = $this->interview->status;
        $this->interview->update([
            'status' => InterviewStatus::CANCELLATION_REFUSAL,
            'reason' => $this->cancelReason,
        ]);

        // Create a new slot
        // if ($this->interview->implementation_date < now()) {
        // }
        $this->createSlotAfterCancellation($this->interview);

        // Send notification
        $this->sendStatusChangeNotification($this->interview, $oldStatus);

        $this->showCancelModal = false;
        $this->cancelReason = '';
        $this->interview->refresh();
        $this->updateConfirmationMessage();
        flash()->success('The interview has been cancelled.');
    }

    private function createSlotAfterCancellation($interview)
    {
        InterviewTimeSlot::create([
            'company_id' => $interview->vacancy->company_id,
            'date' => $interview->implementation_date,
            'start_time' => $interview->implementation_start_time,
            'end_time' => Carbon::parse($interview->implementation_start_time)->addMinutes(10),
            'status' => 'available',
            'user_id' => $interview->incharge_user_id,
            'vacancy_id' => null,
        ]);
    }

    private function sendStatusChangeNotification($interview, $oldStatus)
    {
        $user = Auth::user();
        $recipient = $interview->inchargeUser;
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

    public function render()
    {
        return view('livewire.student.interview-confirmation');
    }
}
