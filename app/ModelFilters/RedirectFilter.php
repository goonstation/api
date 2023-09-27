<?php

namespace App\ModelFilters;

use App\ModelFilters\Common\HasRangeFilters;
use App\ModelFilters\Common\HasTimestampFilters;
use EloquentFilter\ModelFilter;

class RedirectFilter extends ModelFilter
{
    use HasTimestampFilters, HasRangeFilters;

    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];

    public function id($val)
    {
        return $this->where('id', $val);
    }

    public function from($val)
    {
        return $this->where('from', 'ILIKE', '%'.$val.'%');
    }

    public function to($val)
    {
        return $this->where('to', 'ILIKE', '%'.$val.'%');
    }

    public function visits($val)
    {
        return $this->filterRange('visits', $val);
    }
}
