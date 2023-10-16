<?php

namespace App\ModelFilters;

use App\ModelFilters\Common\HasTimestampFilters;
use EloquentFilter\ModelFilter;

class PlayerMetadataFilter extends ModelFilter
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
        return $this->related('player', function ($query) use ($val) {
            return $query->whereLike('ckey', $val);
        });
    }

    public function metadata($val)
    {
        return $this->where('metadata', $val);
    }
}
