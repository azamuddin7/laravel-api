<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidShape implements Rule
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
        $validShapes = ['circle', 'triangle', 'square', 'star', 'rectangle'];
        return in_array($value, $validShapes);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be one of the following shapes: circle, triangle, square, star, rectangle.';
    }
}
