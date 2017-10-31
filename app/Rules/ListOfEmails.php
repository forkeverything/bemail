<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ListOfEmails implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Only want to check if it's emails only if we get a value.
        // Whether the field is required is a different
        // validation rule.
        if(!$value) return true;

        $emails = explode(',', $value);
        foreach ($emails as $email) {
            if(! filter_var($email, FILTER_VALIDATE_EMAIL)) return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid email address given.';
    }
}
