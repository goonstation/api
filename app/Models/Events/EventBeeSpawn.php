<?php

namespace App\Models\Events;

use App\Models\GameRound;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $round_id
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read GameRound $gameRound
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventBeeSpawn filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventBeeSpawn newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventBeeSpawn newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventBeeSpawn paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventBeeSpawn query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventBeeSpawn simplePaginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventBeeSpawn whereBeginsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventBeeSpawn whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventBeeSpawn whereEndsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventBeeSpawn whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventBeeSpawn whereLike($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventBeeSpawn whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventBeeSpawn whereRoundId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventBeeSpawn whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class EventBeeSpawn extends BaseEventModel
{
    use Filterable, HasFactory;

    protected $table = 'events_bee_spawns';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gameRound()
    {
        return $this->belongsTo(GameRound::class, 'round_id');
    }
}
