<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InterviewNotificationCandidate extends Mailable
{
    use Queueable, SerializesModels;

    public $interview;
    public $company;
    public $candidate;
    public $calendarLinks;

    public function __construct($interview, $company, $candidate, $calendarLinks)
    {
        $this->interview = $interview;
        $this->company = $company;
        $this->candidate = $candidate;
        $this->calendarLinks = $calendarLinks;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Interview Notification: ' . $this->interview->vacancy->job_title,
        );
    }

    public function content()
    {
        return new Content(
            view: 'emails.interview-notification-candidate',
        );
    }

    public function attachments()
    {
        return [];
    }
}
