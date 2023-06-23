<?php

namespace App\ModelFilters;

use App\ModelFilters\Common\HasTimestampFilters;
use EloquentFilter\ModelFilter;

class BanFilter extends ModelFilter
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
        return $this->where('server_id', $val);
    }

    public function gameAdmin($val)
    {
        return $this->related('game_admins', function ($query) use ($val) {
            return $query->whereLike('ckey', $val);
        });
    }

    public function reason($val)
    {
        return $this->whereLike('reason', $val);
    }

    public function details($val)
    {
        $val = explode(' ', $val);
        $operator = count($val) === 1 ? 'between' : $val[0];
        $amount = count($val) === 1 ? $val[0] : $val[1];

        if ($operator === 'between') {
            $amount = explode('-', $amount);

            return $this->has('details', '>', $amount[0])->has('details', '<', $amount[1]);
        }

        return $this->has('details', $operator, $amount);
    }

    public function expiresAt($val)
    {
        $val = explode('-', $val);
        $from = date($val[0]);
        $to = date($val[1]);
        if (! $this->validateDate($from) || ! $this->validateDate($to)) {
            return;
        }

        return $this->whereBetween('expires_at', [$from, $to]);
    }
}
