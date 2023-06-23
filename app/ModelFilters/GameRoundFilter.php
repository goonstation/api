<?php

namespace App\ModelFilters;

use App\ModelFilters\Common\HasTimestampFilters;
use EloquentFilter\ModelFilter;

class GameRoundFilter extends ModelFilter
{
    use HasTimestampFilters;

    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];

    public function server($val)
    {
        return $this->where('server_id', 'ILIKE', '%'.$val.'%');
    }

    public function map($val)
    {
        return $this->where('map', 'ILIKE', '%'.$val.'%');
    }

    public function gameType($val)
    {
        return $this->where('game_type', 'ILIKE', '%'.$val.'%');
    }

    public function rpMode($val)
    {
        return $this->where('rp_mode', $val);
    }

    public function crashed($val)
    {
        return $this->where('crashed', $val);
    }

    public function endedAt($val)
    {
        $val = explode('-', $val);
        $from = date($val[0]);
        $to = date($val[1]);
        if (! $this->validateDate($from) || ! $this->validateDate($to)) {
            return;
        }

        return $this->whereBetween('ended_at', [$from, $to]);
    }
}
