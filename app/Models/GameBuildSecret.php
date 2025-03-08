<?php

namespace App\Models;

/**
 * @property int $id
 * @property string $key
 * @property string $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Audit> $audits
 * @property-read int|null $audits_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildSecret newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildSecret newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildSecret query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildSecret whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildSecret whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildSecret whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildSecret whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildSecret whereValue($value)
 *
 * @mixin \Eloquent
 */
class GameBuildSecret extends BaseModel
{
    //
}
