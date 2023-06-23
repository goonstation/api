<?php

namespace App\ModelFilters;

use App\ModelFilters\Common\HasTimestampFilters;
use Carbon\Carbon;
use EloquentFilter\ModelFilter;

class PollFilter extends ModelFilter
{
    use HasTimestampFilters;

    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];

    public function gameAdmin($val)
    {
        return $this->related('game_admins', function ($query) use ($val) {
            return $query->whereLike('ckey', $val);
        });
    }

    public function question($val)
    {
        return $this->whereLike('question', $val);
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

    public function active($val)
    {
        if ($val === 'true') {
            return $this->where('expires_at', null)
                ->orWhere('expires_at', '>', Carbon::now()->toDateTimeString());
        } else {
            return $this->where('expires_at', '!=', null)
                ->where('expires_at', '<', Carbon::now()->toDateTimeString());
        }
    }
}
