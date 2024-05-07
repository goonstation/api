<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class GameServerFilter extends ModelFilter
{
    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];

    public function server($val)
    {
        if ($val === 'all') return;
        return $this->where('server_id', $val);
    }
}
