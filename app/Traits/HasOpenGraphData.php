<?php

namespace App\Traits;

trait HasOpenGraphData
{
    abstract public static function getOpenGraphData(int $id);
}
