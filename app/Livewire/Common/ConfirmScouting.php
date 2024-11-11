<?php

namespace App\Livewire\Common;

use Livewire\Component;
use App\Models\Candidate;
use App\Models\Vacancy;
use App\Models\Interview;
use App\Models\MessageThread;
use App\Models\Message;
use App\Enum\InterviewStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\CandidateScoutedNotification;
use Illuminate\Support\Facades\Gate;

class ConfirmScouting extends Component
{
    public $candidateId;
    public $jobId;
    public $candidate;
    public $job;

    public function mount($candidateId, $jobId)
    {
        $this->candidateId = $candidateId;
        $this->jobId = $jobId;
        $this->candidate = Candidate::findOrFail($candidateId);
        $this->job = Vacancy::findOrFail($jobId);

        if (!Gate::allows('scout', $this->candidate) || !Gate::allows('scoutWith', $this->job)) {
            abort(403, 'Unauthorized action.');
        }
    }

    public function confirmScouting()
    {
        if (!Gate::allows('scout', $this->candidate) || !Gate::allows('scoutWith', $this->job)) {
            abort(403, 'Unauthorized action.');
        }

        $interview = new Interview();
        $interview->candidate_id = $this->candidateId;
        $interview->vacancy_id = $this->jobId;
        $interview->status = InterviewStatus::SCOUTED;
        $interview->booking_request_date_company = Carbon::now();
        $interview->incharge_user_id = auth()->id();
        $interview->save();

        $this->sendScoutingNotificationEmail();
        $this->createScoutingMessage();

        return redirect()->route('job-seeker.view', ['id' => $this->candidateId], flash()->success('Candidate has been Scouted.'));
    }

    private function sendScoutingNotificationEmail()
    {
        $candidateEmail = $this->candidate->student->user->email;
        Mail::to($candidateEmail)->send(new CandidateScoutedNotification($this->candidate, $this->job));
    }

    private function createScoutingMessage()
    {
        $thread = MessageThread::create([
            'title' => 'Scouting Notification: ' . $this->job->job_title,
            'inquiry_type' => 'interview',
        ]);

        Message::create([
            'thread_id' => $thread->id,
            'sender_user_id' => auth()->id(),
            'sender_user_type' => auth()->user()->user_type,
            'receiver_user_id' => $this->candidate->student->user_id,
            'receiver_user_type' => 'Student',
            'sent_at' => now(),
            'content' => $this->generateScoutingMessageContent(),
            'message_category' => 'Sent',
        ]);
    }

    private function generateScoutingMessageContent()
    {
        return "
            You have been scouted for a job opportunity.

            Job Title: {$this->job->job_title}
            Company: {$this->job->company->name}

            To approve and select an interview date and time, please visit the Interview List page in your account.

            Best regards,
            " . config('app.name');
    }

    public function cancelScouting()
    {
        return redirect()->route('job-seeker.view', ['id' => $this->candidateId], flash()->info('Scouting has been Cancelled.'));
    }

    public function desiredJobType()
{
    return $this->belongsTo(VacancyCategory::class, 'desired_job_type')->withDefault([
        'type' => null,
        'name' => 'N/A'
    ]);
}

    public function render()
    {
        return view('livewire.common.confirm-scouting');
    }
}
