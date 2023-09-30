<?php

namespace App\ModelFilters\Common;

trait HasRangeFilters
{
    private function filterRangeRelationship($key, $val)
    {
        if (filter_var($val, FILTER_VALIDATE_INT)) {
            $operator = '=';
            $amount = $val;
        } else {
            $val = explode(' ', $val);
            $operator = count($val) === 1 ? 'between' : $val[0];
            $amount = count($val) === 1 ? $val[0] : $val[1];
        }

        if ($operator === 'between') {
            $amount = explode('-', $amount);

            return $this->has($key, '>', $amount[0])->has($key, '<', $amount[1]);
        }

        return $this->has($key, $operator, $amount);
    }

    private function filterRange($key, $val)
    {
        if (filter_var($val, FILTER_VALIDATE_INT)) {
            $operator = '=';
            $amount = $val;
        } else {
            $val = explode(' ', $val);
            $operator = count($val) === 1 ? 'between' : $val[0];
            $amount = count($val) === 1 ? $val[0] : $val[1];
        }

        if ($operator === 'between') {
            $amount = explode('-', $amount);

            return $this->where($key, '>', $amount[0])->where($key, '<', $amount[1]);
        }

        return $this->where($key, $operator, $amount);
    }
}
