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

    public function ckey($val)
    {
        return $this->whereLike('ckey', $val);
    }

    public function key($val)
    {
        return $this->whereLike('key', $val)->whereLike('ckey', ckey($val), 'or');
    }

    public function rank($val)
    {
        return $this->whereRelation('rank', 'rank', '=', $val);
    }
}
