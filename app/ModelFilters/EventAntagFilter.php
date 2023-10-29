<?php

namespace App\ModelFilters;

use App\ModelFilters\Common\HasRangeFilters;
use App\ModelFilters\Common\HasTimestampFilters;
use EloquentFilter\ModelFilter;

class EventAntagFilter extends ModelFilter
{
    use HasTimestampFilters, HasRangeFilters;

    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];

    public function mobName($val)
    {
        return $this->where('mob_name', 'ILIKE', '%'.$val.'%');
    }

    public function mobJob($val)
    {
        return $this->where('mob_job', 'ILIKE', '%'.$val.'%');
    }

    public function traitorType($val)
    {
        return $this->where('traitor_type', 'ILIKE', '%'.$val.'%');
    }

    public function success($val)
    {
        return $this->where('success', $val);
    }
}
