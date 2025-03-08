<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property int $voteable_id
 * @property string $voteable_type
 * @property string $ip
 * @property int $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Model|\Eloquent $voteable
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vote query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vote whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vote whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vote whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vote whereVoteableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vote whereVoteableType($value)
 *
 * @mixin \Eloquent
 */
class Vote extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'ip',
        'value',
    ];

    public static $auditingDisabled = true;

    public function voteable(): MorphTo
    {
        return $this->morphTo();
    }
}
