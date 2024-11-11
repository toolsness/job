<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class JapaneseName implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match('/^[\x{3000}-\x{303F}\x{3040}-\x{309F}\x{30A0}-\x{30FF}\x{FF00}-\x{FFEF}\x{4E00}-\x{9FAF}\x{3400}-\x{4DBF}\s]+$/u', $value)) {
            $fail('The :attribute must contain only Japanese characters (Kanji, Hiragana, Katakana) and spaces.');
        }
        if (mb_strlen($value) < 2) {
            $fail('The :attribute must be at least 2 characters long.');
        }
        if (mb_strlen($value) > 20) {
            $fail('The :attribute may not be greater than 20 characters.');
        }
    }
}
