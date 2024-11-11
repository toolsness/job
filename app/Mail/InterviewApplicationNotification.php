<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InterviewApplicationNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $interview;

    public function __construct($interview)
    {
        $this->interview = $interview;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'New Interview Application Received',
        );
    }

    public function content()
    {
        return new Content(
            view: 'emails.interview-application-notification',
        );
    }

    public function attachments()
    {
        return [];
    }
}
