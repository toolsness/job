<?php

namespace App\Livewire\Company\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;


class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;
    public $errorMessage = '';
    public $remainingAttempts = 3;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
        $this->errorMessage = '';
    }

    public function login()
    {
        $this->validate();

        $key = 'login.' . Str::lower($this->email);
        $maxAttempts = 3;
        $decayMinutes = 2;

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            $this->errorMessage = "Too many login attempts. Please try again in {$seconds} seconds or reset your password.";
            return;
        }

        $user = User::where('email', $this->email)->first();

        if (!$user || !$this->checkUserType($user)) {
            $this->handleFailedAttempt($key, $maxAttempts, $decayMinutes);
            return;
        }

        if ($user->login_permission_category === 'Pending') {
            $this->errorMessage = 'Your account is pending approval. Please wait for admin confirmation.';
            return;
        }

        if ($user->login_permission_category === 'NotAllowed') {
            $this->errorMessage = 'Your account has been rejected. Please contact the administrator.';
            return;
        }

        $remember = $this->remember;

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $remember)) {
            RateLimiter::clear($key);

            if ($remember) {
                // Set the "remember me" cookie to expire in 2 months
                $rememberDuration = 60 * 24 * 60; // 60 days
                Cookie::queue(Auth::getRecallerName(), Cookie::get(Auth::getRecallerName()), $rememberDuration);
            }

            return redirect()->intended(route('home'));
        }

        $this->handleFailedAttempt($key, $maxAttempts, $decayMinutes);
    }

    private function checkUserType($user)
    {
        return in_array($user->user_type, ['CompanyRepresentative', 'CompanyAdmin']);
    }

    private function handleFailedAttempt($key, $maxAttempts, $decayMinutes)
    {
        RateLimiter::hit($key, $decayMinutes * 60);
        $this->remainingAttempts = RateLimiter::remaining($key, $maxAttempts);

        if ($this->remainingAttempts > 0) {
            $this->errorMessage = "Invalid email or password. {$this->remainingAttempts} " .
                ($this->remainingAttempts === 1 ? "attempt" : "attempts") . " remaining.";
        } else {
            $seconds = RateLimiter::availableIn($key);
            $this->errorMessage = "Too many login attempts. Please try again in {$seconds} seconds or reset your password.";
        }
    }

    public function render()
    {
        return view('livewire.company.auth.login');
    }
}
