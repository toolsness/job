<?php

namespace App\Livewire\Student\Auth;

use Ichtrojan\Otp\Otp;
use Livewire\Component;
use App\Mail\VerifyEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Rules\EmailConfirmation;

class NewMemberRegistration extends Component
{
    public $email = '';
    public $confirmEmail = '';

    protected function rules()
    {
        return [
            'email' => ['required', 'email:rfc,dns', 'unique:users,email'],
            'confirmEmail' => ['required', new EmailConfirmation($this->email, $this->confirmEmail)],
        ];
    }

    protected function messages()
    {
        return [
            'email.required' => 'The email field is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'confirmEmail.required' => 'Please confirm your email address.',
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function updatedEmail()
    {
        $this->validateOnly('email');
        $this->validateOnly('confirmEmail');
    }

    public function updatedConfirmEmail()
    {
        $this->validateOnly('confirmEmail');
    }
    
    public function sendVerificationEmail()
    {
        $this->validate();

        try {
            $otp = new Otp();
            $verificationToken = $otp->generate($this->email, 'alpha_numeric', 6, 60)->token; // 6 length with 60 minutes validity

            $expirationDateTime = now()->addHour();
            $verificationUrl = route('student.email.verify', ['token' => $verificationToken, 'email' => $this->email]);

            Mail::to($this->email)->send(new VerifyEmail($verificationUrl, $expirationDateTime));

            flash()->success('Verification email sent successfully!');

            $this->reset(['email', 'confirmEmail']);
            $this->dispatch('form-reset');
        } catch (\Exception $e) {
            flash()->error('An error occurred while sending the verification email. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.student.auth.new-member-registration');
    }
}
