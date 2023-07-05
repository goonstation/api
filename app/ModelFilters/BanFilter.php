<?php

namespace App\ModelFilters;

use App\ModelFilters\Common\HasRangeFilters;
use App\ModelFilters\Common\HasTimestampFilters;
use EloquentFilter\ModelFilter;

class BanFilter extends ModelFilter
{
    use HasTimestampFilters, HasRangeFilters;

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

    public function adminCkey($val)
    {
        return $this->related('gameAdmin', function ($query) use ($val) {
            return $query->where('name', 'ILIKE', '%'.$val.'%')
                    ->orWhere('ckey', 'ILIKE', '%'.$val.'%');
        });
    }

    public function reason($val)
    {
        return $this->where('reason', 'ILIKE', '%'.$val.'%');
    }

    public function originalBanCkey($val)
    {
        return $this->related('originalBanDetail', function ($query) use ($val) {
            return $query->where('ckey', 'ILIKE', '%'.$val.'%');
        });
    }

    public function details($val)
    {
        return $this->filterRangeRelationship('details', $val);
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
