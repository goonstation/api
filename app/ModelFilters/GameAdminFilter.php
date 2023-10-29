<?php

namespace App\ModelFilters;

use App\ModelFilters\Common\HasTimestampFilters;
use EloquentFilter\ModelFilter;

class GameAdminFilter extends ModelFilter
{
    use HasTimestampFilters;

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

    public function ckey($val)
    {
        return $this->where('ckey', 'ILIKE', '%'.$val.'%');
    }

    public function name($val)
    {
        return $this->where('name', 'ILIKE', '%'.$val.'%');
    }

    public function rank($val)
    {
        return $this->where('rank_id', $val);
    }
}
