<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $verificationUrl;
    public $expirationDateTime;

    public function __construct($verificationUrl, $expirationDateTime)
    {
        $this->verificationUrl = $verificationUrl;
        $this->expirationDateTime = $expirationDateTime;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Verify Your Email Address to Register New Account',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.verify',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
