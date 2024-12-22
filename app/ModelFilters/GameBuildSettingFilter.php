<?php

namespace App\ModelFilters;

use App\ModelFilters\Common\HasRangeFilters;
use App\ModelFilters\Common\HasTimestampFilters;
use EloquentFilter\ModelFilter;

class GameBuildSettingFilter extends ModelFilter
{
    use HasRangeFilters, HasTimestampFilters;

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

    public function server($val)
    {
        return $this->where('server_id', $val);
    }

    public function branch($val)
    {
        return $this->where('branch', $val);
    }

    public function byondMajor($val)
    {
        return $this->filterRange('byond_major', $val);
    }

    public function byondMinor($val)
    {
        return $this->filterRange('byond_minor', $val);
    }

    public function rustgVersion($val)
    {
        return $this->where('rustg_version', $val);
    }

    public function rpMode($val)
    {
        return $this->where('rp_mode', $val);
    }

    public function mapId($val)
    {
        return $this->where('map_id', $val);
    }
}
