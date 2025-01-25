<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    public static function getTableName()
    {
        // @phpstan-ignore larastan.uselessConstructs.with,new.static
        return with(new static)->getTable();
    }
}
