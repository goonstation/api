<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class DateRange implements InvokableRule
{
    private function validateDate($date, $format = 'Y/m/d H:i:s')
    {
        $d = \DateTime::createFromFormat($format, $date);

        return $d && $d->format($format) === $date;
    }

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
        $validDate = $this->validateDate($value);
        if (! str_contains($value, '-') && ! $validDate) {
            return $fail('The :attribute must be formatted as YYYY/MM/DD HH:mm:ss, or contain a - to denote a range');
        }

        if ($validDate) {
            return;
        }

        $val = explode('-', $value);

        if (count($val) > 2) {
            return $fail('The :attribute must contain only one -');
        }

        $from = date(trim($val[0]));
        $to = date(trim($val[1]));

        if (! $this->validateDate($from) || ! $this->validateDate($to)) {
            return $fail('The :attribute must have dates formatted as YYYY/MM/DD HH:mm:ss');
        }
    }
}
