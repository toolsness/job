<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EmailConfirmation implements ValidationRule
{
    protected $email;
    protected $confirmEmail;

    public function __construct($email, $confirmEmail)
    {
        $this->email = $email;
        $this->confirmEmail = $confirmEmail;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($this->email) && !empty($this->confirmEmail)) {
            $fail('The email field cannot be empty.');
        } elseif (!empty($this->email) && !empty($this->confirmEmail) && $this->email !== $this->confirmEmail) {
            $fail('The email addresses do not match.');
        }
    }
}
