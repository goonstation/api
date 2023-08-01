<?php

namespace App\ModelFilters;

use App\ModelFilters\Common\HasRangeFilters;
use App\ModelFilters\Common\HasTimestampFilters;
use EloquentFilter\ModelFilter;

class PlayerFilter extends ModelFilter
{
    use HasRangeFilters, HasTimestampFilters;

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

    public function connections($val)
    {
        return $this->filterRangeRelationship('connections', $val);
    }

    public function participations($val)
    {
        return $this->filterRangeRelationship('participations', $val);
    }

    public function byondVersion($val)
    {
        $val = explode('.', $val);
        $major = $val[0];
        $minor = null;
        if (count($val) > 1) {
            $minor = $val[1];
        }

        $query = $this->where('byond_major', $major);
        if ($minor) {
            $query = $query->where('byond_minor', $minor);
        }

        return $query;
    }
}
