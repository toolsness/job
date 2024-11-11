<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EnglishName implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match('/^[a-zA-Z\s]+$/', $value)) {
            $fail('The :attribute must contain only English letters.');
        }
        if (strlen($value) < 2) {
            $fail('The :attribute must be at least 2 characters long.');
        }
        if (strlen($value) > 50) {
            $fail('The :attribute may not be greater than 50 characters.');
        }
    }
}
