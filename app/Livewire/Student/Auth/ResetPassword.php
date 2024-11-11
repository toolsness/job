<?php

namespace App\Livewire\Student\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use App\Rules\ComplexPassword;
use Livewire\Attributes\Rule;
use App\Mail\PasswordResetSuccessfulMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Flasher\Prime\FlasherInterface;

class ResetPassword extends Component
{
    public $token;
    public $email;

    #[Rule(['required', new ComplexPassword])]
    public $password = '';

    #[Rule(['required', 'same:password'])]
    public $password_confirmation = '';

    public function mount($token)
{
    $this->token = $token;
    $this->email = request()->query('email');

    if (!$this->isValidToken()) {
        flash()->error('The password reset link has expired. Please request a new one.');
        return redirect()->route('student.password.request');
    }
}

public function resetPassword()
{
    $this->validate();

    $tokenData = DB::table('password_reset_tokens')
        ->where('token', $this->token)
        ->where('email', $this->email)
        ->first();

        if (!$tokenData) {
            flash()->error('Invalid token or email.');
            return redirect()->route('student.password.request');
        }

    $user = User::where('email', $this->email)
                ->whereIn('user_type', ['Student', 'Candidate'])
                ->first();

                if (!$user) {
                    flash()->error('Student or candidate account not found.');
                    return redirect()->route('student.password.request');
                }

    $user->password = Hash::make($this->password);
    $user->setRememberToken(Str::random(60));
    $user->save();

    DB::table('password_reset_tokens')->where('email', $user->email)->delete();

    event(new PasswordReset($user));

    Mail::to($user->email)->send(new PasswordResetSuccessfulMail());

    flash()->success('Your password has been reset successfully. You can now log in with your new password.');
    return redirect()->route('student.login');
}

    private function isValidToken()
    {
        return DB::table('password_reset_tokens')
            ->where('token', $this->token)
            ->where('email', $this->email)
            ->where('created_at', '>', now()->subMinutes(5)) // Token expires after 5 minutes
            ->exists();
    }

    public function updatedPassword()
    {
        $this->validateOnly('password');
    }

    public function updatedPasswordConfirmation()
    {
        $this->validateOnly('password_confirmation');
    }

    public function render()
    {
        return view('livewire.student.auth.reset-password');
    }
}
