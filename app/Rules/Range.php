<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class Range implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        $isNumber = filter_var($value, FILTER_VALIDATE_INT);
        if (!str_contains($value, ' ') && !str_contains($value, '-') && !$isNumber) {
            return $fail('The :attribute must either be a number, or contain whitespace or a -');
        }

        if ($isNumber) return;

        $val = explode(' ', $value);

        if (count($val) > 2) {
            return $fail('The :attribute cannot contain more than one whitespace');
        }

        if (count($val) === 2) {
            $rangeOperators = ['<', '<=', '>', '>=', '='];
            if (!in_array($val[0], $rangeOperators)) {
                return $fail('The :attribute left hand side must equal one of: ' . implode(', ', $rangeOperators));
            }
            if (!filter_var($val[1], FILTER_VALIDATE_INT)) {
                return $fail('The :attribute right hand side must be an integer');
            }
        } else {
            $betweenAmounts = explode('-', $val[0]);
            if (count($betweenAmounts) > 2) {
                return $fail('The :attribute cannot contain more than one -');
            }
            if (!filter_var($betweenAmounts[0], FILTER_VALIDATE_INT) || !filter_var($betweenAmounts[1], FILTER_VALIDATE_INT)) {
                return $fail('The :attribute left and right hand side must be an integer.');
            }
        }
    }
}
