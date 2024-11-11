<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InterviewStatusChangeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $details;

    public function __construct($subject, $details)
    {
        $this->subject = $subject;
        $this->details = $details;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.interview-status-change',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
