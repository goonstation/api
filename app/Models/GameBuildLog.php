<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $build_id
 * @property string|null $type
 * @property string|null $group
 * @property string $log
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\GameBuild $gameBuild
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildLog whereBuildId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildLog whereGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildLog whereLog($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildLog whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildLog whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class GameBuildLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'build_id',
        'log',
        'type',
        'group',
    ];

    public function gameBuild(): BelongsTo
    {
        return $this->belongsTo(GameBuild::class, 'build_id');
    }
}
