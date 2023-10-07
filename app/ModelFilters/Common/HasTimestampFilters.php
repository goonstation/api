<?php

namespace App\ModelFilters\Common;

trait HasTimestampFilters
{
    private function filterDate($key, $val)
    {
        if ($val === 'false' || $val === 'null') {
            return $this->where($key, '=', null);
        }

        $val = explode('-', $val);
        $from = date($val[0]);

        if (count($val) === 2) {
            $to = date($val[1]);

            return $this->whereBetween($key, [$from, $to]);
        } else {
            return $this->where($key, '=', $from);
        }
    }

    public function createdAt($val)
    {
        return $this->filterDate('created_at', $val);
    }

    public function updatedAt($val)
    {
        return $this->filterDate('updated_at', $val);
    }
}
