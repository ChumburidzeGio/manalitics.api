<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Consts\AllowedCurrencyCodes;

class Currency implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return AllowedCurrencyCodes::is_valid($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute cannot be an empty string.';
    }
}