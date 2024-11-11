<?php

namespace App\Livewire\Company\Auth;

use App\Mail\CompanyResetPasswordMail;
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
                ->whereIn('user_type', ['CompanyRepresentative', 'CompanyAdmin'])
                ->first();

                if (!$user) {
                    flash()->error('We can\'t find a company representative or admin account with that email address.');
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

    Mail::to($this->email)->send(new CompanyResetPasswordMail($token, $this->email));

    flash()->success('We have emailed your password reset link. Please check your inbox.');
    $this->reset('email');
}

    public function render()
    {
        return view('livewire.company.auth.forgot-password');
    }
}
