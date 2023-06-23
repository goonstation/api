<?php

namespace App\ModelFilters\Common;

trait HasTimestampFilters
{
    private function validateDate($date, $format = 'Y/m/d')
    {
        $d = \DateTime::createFromFormat($format, $date);

        return $d && $d->format($format) === $date;
    }

    public function createdAt($val)
    {
        $val = explode('-', $val);
        $from = date($val[0]);
        $to = date($val[1]);
        if (! $this->validateDate($from) || ! $this->validateDate($to)) {
            return;
        }

        return $this->whereBetween('created_at', [$from, $to]);
    }

    public function updatedAt($val)
    {
        $val = explode('-', $val);
        $from = date($val[0]);
        $to = date($val[1]);
        if (! $this->validateDate($from) || ! $this->validateDate($to)) {
            return;
        }

        return $this->whereBetween('updated_at', [$from, $to]);
    }
}
