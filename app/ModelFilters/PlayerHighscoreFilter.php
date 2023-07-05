<?php

namespace App\ModelFilters;

use App\ModelFilters\Common\HasRangeFilters;
use App\ModelFilters\Common\HasTimestampFilters;
use EloquentFilter\ModelFilter;

class PlayerHighscoreFilter extends ModelFilter
{
    use HasTimestampFilters, HasRangeFilters;

    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];

    public function ckey($val)
    {
        return $this->related('player', function ($query) use ($val) {
            return $query->where('ckey', 'ILIKE', '%'.$val.'%')
                ->orWhere('key', 'ILIKE', '%'.$val.'%');
        });
    }

    public function type($val)
    {
        return $this->where('type', $val);
    }

    public function value($val)
    {
        return $this->filterRange('value', $val);
    }
}
