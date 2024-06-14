<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class PersonalAccessToken extends SanctumPersonalAccessToken
{
    protected function lastUsedAt(): Attribute
    {
        return Attribute::make(
            set: function (mixed $value): void {
                // disable updating the last_used_at attribute as it's not used

            },
        );
    }
}
