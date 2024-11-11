<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PasswordConfirmation implements ValidationRule
{
    protected $password;
    protected $confirmPassword;

    public function __construct($password, $confirmPassword)
    {
        $this->password = $password;
        $this->confirmPassword = $confirmPassword;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($this->password) && !empty($this->confirmPassword)) {
            $fail('The password field cannot be empty.');
        } elseif (!empty($this->password) && !empty($this->confirmPassword) && $this->password !== $this->confirmPassword) {
            $fail('The passwords do not match.');
        }
    }
}
