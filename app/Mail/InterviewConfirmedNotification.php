<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InterviewConfirmedNotification extends Mailable
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
            subject: 'Interview Confirmed',
        );
    }

    public function content()
    {
        return new Content(
            view: 'emails.interview-confirmed-notification',
        );
    }

    public function attachments()
    {
        return [];
    }
}
