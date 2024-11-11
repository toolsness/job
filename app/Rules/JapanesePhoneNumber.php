<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class JapanesePhoneNumber implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match('/^[0-9０-９]+$/', $value)) {
            $fail('The :attribute must contain only numerical characters (including Japanese numerals).');
        }
    }
}
