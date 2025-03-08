<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property string $key
 * @property string|null $stats
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Audit> $audits
 * @property-read int|null $audits_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GlobalStat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GlobalStat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GlobalStat query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GlobalStat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GlobalStat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GlobalStat whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GlobalStat whereStats($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GlobalStat whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class GlobalStat extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'key',
        'stats',
    ];

    public static $auditingDisabled = true;
}
