<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditingAuditable;
use OwenIt\Auditing\Contracts\Auditable;

abstract class BaseModel extends Model implements Auditable
{
    use AuditingAuditable;

    public static function getTableName()
    {
        // @phpstan-ignore larastan.uselessConstructs.with,new.static
        return with(new static)->getTable();
    }
}
