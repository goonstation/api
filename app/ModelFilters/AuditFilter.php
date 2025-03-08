<?php

namespace App\ModelFilters;

use App\ModelFilters\Common\HasRangeFilters;
use App\ModelFilters\Common\HasTimestampFilters;
use EloquentFilter\ModelFilter;

class AuditFilter extends ModelFilter
{
    use HasRangeFilters, HasTimestampFilters;

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

    public function user($val)
    {
        return $this->where('user_id', $val);
    }

    public function event($val)
    {
        return $this->where('event', 'ILIKE', '%'.$val.'%');
    }

    public function auditableType($val)
    {
        return $this->where('auditable_type', "App\\Models\\$val");
    }
}
