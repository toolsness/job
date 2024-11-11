<?php

namespace App\Livewire\BusinessOperator\Auth;

use App\Mail\BusinessOperatorResetPasswordMail;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Rule;

class ForgotPassword extends Component
{
    #[Rule('required|email')]
    public $email = '';

    public function sendResetLink()
    {
        $this->validate();

        $user = User::where('email', $this->email)
                    ->where('user_type', 'BusinessOperator')
                    ->first();

        if (!$user) {
            flash()->error('We can\'t find a business operator account with that email address.');
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

        Mail::to($this->email)->send(new BusinessOperatorResetPasswordMail($token, $this->email));

        flash()->success('We have emailed your password reset link!');
        $this->reset('email');
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function render()
    {
        return view('livewire.business-operator.auth.forgot-password');
    }
}
