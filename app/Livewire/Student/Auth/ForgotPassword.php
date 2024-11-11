<?php

namespace App\Livewire\Student\Auth;

use App\Mail\StudentResetPasswordMail;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Rule;
use Flasher\Prime\FlasherInterface;

class ForgotPassword extends Component
{
    #[Rule('required|email')]
    public $email = '';

    public function sendResetLink()
{
    $this->validate();

    $user = User::where('email', $this->email)
                ->whereIn('user_type', ['Student', 'Candidate'])
                ->first();

                if (!$user) {
                    flash()->error('We can\'t find a student or candidate account with that email address.');
                    return;
                }

    $token = Str::random(64);

    DB::table('password_reset_tokens')->updateOrInsert(
        ['email' => $this->email],
        [
            'email' => $this->email,
            'token' => $token,
            'created_at' => now()
        ]
    );

    Mail::to($this->email)->send(new StudentResetPasswordMail($token, $this->email));

    flash()->success('We have emailed your password reset link. Please check your inbox.');
    $this->reset('email');
}

    public function render()
    {
        return view('livewire.student.auth.forgot-password');
    }
}
