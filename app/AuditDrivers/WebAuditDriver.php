<?php

namespace App\AuditDrivers;

use OwenIt\Auditing\Contracts\Audit;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Contracts\AuditDriver;
use OwenIt\Auditing\Drivers\Database;

class WebAuditDriver extends Database implements AuditDriver
{
    public function audit(Auditable $model): ?Audit
    {
        $request = request();
        $isAdminApiRequest = in_array('auth:api', $request->route()->middleware());

        // Until more granular roles exist, avoid the performance hit on game API requests this way
        if ($isAdminApiRequest) {
            return null;
        }

        return parent::audit($model);
    }

    public function prune(Auditable $model): bool
    {
        return parent::prune($model);
    }
}
