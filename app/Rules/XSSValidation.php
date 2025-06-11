<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Log;

class XSSValidation implements ValidationRule
{
    /**
     * Validate against XSS attacks.
     * If the input has any special characters, then fail the validation check
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (is_string($value) && htmlspecialchars($value) != $value) {
            $fail('The :attribute must not contain HTML tags or other special characters.');
        }
    }
}
