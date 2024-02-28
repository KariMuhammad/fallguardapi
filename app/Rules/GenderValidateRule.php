<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class GenderValidateRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //
        $existInArray = in_array(strtolower($value), [
            "male", "female"
        ]);

        if (!$existInArray) {
            $fail("The $attribute must be either 'male' or 'female'.");
        }
    }
}
