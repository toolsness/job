<?php

namespace App\Livewire\Common;

use Livewire\Component;
use App\Models\Interview;
use App\Models\InterviewMemo;
use App\Models\MessageThread;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Spatie\CalendarLinks\Link;
use App\Mail\InterviewNotificationCandidate;
use Illuminate\Support\Facades\Mail;


class InterviewDetails extends Component
{
    public $interview;
    public $activeTab = 'interview';
    public $memoContent = '';
    public $showZoomLinkModal = false;
    public $zoomLink = '';

    protected $rules = [
        'memoContent' => 'required|string|max:1000',
        'zoomLink' => 'nullable|url',
    ];

    public function mount(Interview $interview)
    {
        $this->interview = $interview->load('interviewSchedule', 'vacancy.companyRepresentative', 'student.candidate', 'memos.user', 'inchargeUser');
        $this->zoomLink = $this->interview->zoom_link ?? '';
    }

    public function returnToInterviewList()
    {
        return redirect()->route('job-interviews', ['vacancyId' => $this->interview->vacancy_id]);
    }

    public function saveMemo()
    {
        $this->validate();

        $memo = new InterviewMemo([
            'interview_id' => $this->interview->id,
            'user_id' => Auth::id(),
            'content' => $this->memoContent,
        ]);

        $memo->save();

        $this->interview->refresh();
        $this->memoContent = '';
        $this->dispatch('memoSaved');
    }

    public function openZoomLinkModal()
    {
        $this->showZoomLinkModal = true;
    }

    public function closeZoomLinkModal()
    {
        $this->showZoomLinkModal = false;
    }

    public function saveZoomLink()
    {
        $this->validate(['zoomLink' => 'required|url']);

        try {
            $this->interview->update(['zoom_link' => $this->zoomLink]);
            $this->interview->refresh();
            $this->closeZoomLinkModal();
            $this->dispatch('zoomLinkSaved');

            flash()->success('Zoom link saved successfully.');
        } catch (\Exception $e) {
            flash()->error('Error saving Zoom link: ' . $e->getMessage());
        }
    }

    public function getGoogleCalendarLink()
    {
        return $this->getCalendarLink()->google();
    }

    public function getAppleCalendarLink()
    {
        return $this->getCalendarLink()->ics();
    }

    public function getOutlookCalendarLink()
    {
        return $this->getCalendarLink()->webOutlook();
    }

    private function getCalendarLink()
    {
        $from = Carbon::parse($this->interview->interviewSchedule->interview_date->format('Y-m-d') . ' ' . $this->interview->interviewSchedule->interview_start_time);
        $to = $from->copy()->addHour();

        return Link::create(
            'Interview: ' . $this->interview->vacancy->job_title,
            $from,
            $to
        )
            ->description('Interview for ' . $this->interview->vacancy->job_title)
            ->address($this->interview->zoom_link ?? 'No Zoom link provided');
    }

    public function sendNotification()
    {
        $candidate = $this->interview->candidate;
        $company = $this->interview->vacancy->company;

        $calendarLinks = [
            'google' => $this->getGoogleCalendarLink(),
            'ics' => $this->getAppleCalendarLink(),
            'outlook' => $this->getOutlookCalendarLink(),
        ];

        Mail::to($candidate->student->user->email)->send(new InterviewNotificationCandidate($this->interview, $company, $candidate, $calendarLinks));

        $this->createInterviewNotificationMessage($candidate, $company, $calendarLinks);

        $this->dispatch('notificationSent');

        flash()->success('Notification sent successfully.');
    }

    private function createInterviewNotificationMessage($candidate, $company, $calendarLinks)
    {
        $thread = MessageThread::create([
            'title' => 'Interview Notification: ' . $this->interview->vacancy->job_title,
            'inquiry_type' => 'interview',
        ]);

        Message::create([
            'thread_id' => $thread->id,
            'sender_user_id' => Auth::id(),
            'sender_user_type' => Auth::user()->user_type,
            'receiver_user_id' => $candidate->student->user_id,
            'receiver_user_type' => 'Student',
            'sent_at' => now(),
            'content' => $this->generateInterviewNotificationContent($company, $calendarLinks),
            'message_category' => 'Sent',
        ]);
    }

    private function generateInterviewNotificationContent($company, $calendarLinks)
    {
        $interviewLink = route('interview.details', ['interview' => $this->interview->id]);
        $interviewDate = $this->interview->interviewSchedule->interview_date->format('Y-m-d');
        $interviewTime = $this->interview->interviewSchedule->interview_start_time
            ? Carbon::parse($this->interview->interviewSchedule->interview_start_time)->format('H:i')
            : 'Not set';

        return "
            You have an upcoming interview scheduled with {$company->name} for the position of {$this->interview->vacancy->job_title}.

            Interview Details:
            Date: {$interviewDate}
            Time: {$interviewTime}
            " . ($this->interview->zoom_link ? "Zoom Link: {$this->interview->zoom_link}" : "Zoom link not available. You will be notified when it's ready.") . "

            For more details, please visit: {$interviewLink}

            Good luck with your interview!
        ";
    }

    public function render()
    {
        return view('livewire.common.interview-details');
    }
}
