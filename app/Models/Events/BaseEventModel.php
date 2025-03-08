<?php

namespace App\Models\Events;

use App\Models\BaseModel;

abstract class BaseEventModel extends BaseModel
{
    public static $auditingDisabled = true;
}
