<?php

namespace App\ModelFilters\Common;

use Illuminate\Support\Facades\DB;

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

            return $this->has($key, '>', (int) $amount[0])->has($key, '<', (int) $amount[1]);
        }

        return $this->has($key, $operator, (int) $amount);
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

            return $this->where($key, '>', (int) $amount[0])->where($key, '<', (int) $amount[1]);
        }

        return $this->where($key, $operator, (int) $amount);
    }

    private function filterRangeHaving($agg, $key, $val)
    {
        $keyCol = DB::raw($agg.'('.$key.')');
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

            return $this->having($keyCol, '>', (int) $amount[0])
                ->having($keyCol, '<', (int) $amount[1]);
        }

        return $this->having($keyCol, $operator, (int) $amount);
    }
}
