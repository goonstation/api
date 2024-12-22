<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read \App\Models\GameServer|null $gameServer
 * @property-read \App\Models\Map|null $map
 * @property-read \App\Models\GameAdmin|null $startedBy
 * @property-read \App\Models\GameAdmin|null $cancelledBy
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuild newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuild newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuild query()
 *
 * @property int $id
 * @property string $server_id
 * @property int|null $started_by
 * @property string|null $branch
 * @property string|null $commit
 * @property string|null $map_id
 * @property bool $failed
 * @property bool $cancelled
 * @property bool $map_switch
 * @property int|null $cancelled_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $ended_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuild whereBranch($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuild whereCancelled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuild whereCommit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuild whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuild whereEndedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuild whereFailed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuild whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuild whereMap($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuild whereServerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuild whereStartedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuild whereUpdatedAt($value)
 *
 * @property string|null $map_id
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuild whereMapId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuild filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuild paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuild simplePaginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuild whereBeginsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuild whereCancelledBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuild whereEndsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuild whereLike($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuild whereMapSwitch($value)
 *
 * @mixin \Eloquent
 */
class GameBuild extends Model
{
    use Filterable, HasFactory;

    protected $casts = [
        'ended_at' => 'datetime',
    ];

    public function gameServer(): BelongsTo
    {
        return $this->belongsTo(GameServer::class, 'server_id', 'server_id');
    }

    public function startedBy(): BelongsTo
    {
        return $this->belongsTo(GameAdmin::class, 'started_by');
    }

    public function map(): BelongsTo
    {
        return $this->belongsTo(Map::class, 'map_id', 'map_id');
    }

    public function cancelledBy(): BelongsTo
    {
        return $this->belongsTo(GameAdmin::class, 'cancelled_by');
    }
}
