<?php

namespace App\ModelFilters;

use App\ModelFilters\Common\HasRangeFilters;
use App\ModelFilters\Common\HasTimestampFilters;
use EloquentFilter\ModelFilter;

class EventFineFilter extends ModelFilter
{
    use HasTimestampFilters, HasRangeFilters;

    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];

    public function target($val)
    {
        return $this->where('target', 'ILIKE', '%'.$val.'%');
    }

    public function reason($val)
    {
        return $this->where('reason', 'ILIKE', '%'.$val.'%');
    }

    public function issuer($val)
    {
        return $this->where('issuer', 'ILIKE', '%'.$val.'%');
    }

    public function amount($val)
    {
        return $this->filterRange('amount', $val);
    }
}
