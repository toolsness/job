<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CandidateScoutedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $candidate;
    public $job;

    public function __construct($candidate, $job)
    {
        $this->candidate = $candidate;
        $this->job = $job;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'You Have Been Scouted for a Job Opportunity',
        );
    }

    public function content()
    {
        return new Content(
            view: 'emails.candidate-scouted-notification',
        );
    }

    public function attachments()
    {
        return [];
    }
}
