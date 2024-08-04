<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidHost implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! self::isValidFDQN($value) && ! self::isValidIP($value)) {
            $fail('The :attribute must be a valid Hostname or IP Address');
        }
    }

    private function isValidFDQN($value)
    {
        // Regex for validating FQDN (Fully Qualified Domain Name)
        return preg_match('/^(?!:\/\/)(?=.{1,255}$)((.{1,63}\.){1,127}(?![0-9]*$)[a-z0-9-]+\.?)$/i', $value);
    }

    private function isValidIP($value)
    {
        // Use Laravel's built-in IP validation
        return filter_var($value, FILTER_VALIDATE_IP) !== false;
    }
}
