<?php

// app/Rules/PasswordRule.php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PasswordRule implements Rule
{
    public function passes($attribute, $value)
    {
        // Define your regex pattern for password validation
        $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/';
        
        // Check if the value matches the regex pattern
        return preg_match($pattern, $value);
    }

    public function message()
    {
        return 'The :attribute must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, and one number.';
    }
}

