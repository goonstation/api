<?php

namespace App\ModelFilters;

use App\ModelFilters\Common\HasTimestampFilters;
use EloquentFilter\ModelFilter;

class EventCyborgModuleSelectionFilter extends ModelFilter
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

    public function player($val)
    {
        return $this->where('player_id', $val);
    }

    public function round($val)
    {
        return $this->where('round_id', $val);
    }

    public function module($val)
    {
        return $this->where('module', $val);
    }

    public function borg_type($val)
    {
        return $this->where('borg_type', $val);
    }
}
