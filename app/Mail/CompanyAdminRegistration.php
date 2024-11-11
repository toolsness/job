<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CompanyAdminRegistration extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $password;
    public $companyName;

    public function __construct($name, $email, $password, $companyName)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->companyName = $companyName;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Company Admin Account Registration Successful',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.company-admin-registration',
        );
    }
}
