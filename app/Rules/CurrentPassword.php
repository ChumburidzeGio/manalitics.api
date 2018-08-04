<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Consts\AllowedCurrencyCodes;

class CurrentPassword implements Rule
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
        $user = app('auth')->user();

        return $user && app('hash')->check($value, $user->password);
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