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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventStationName filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventStationName newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventStationName newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventStationName paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventStationName query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventStationName simplePaginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventStationName whereBeginsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventStationName whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventStationName whereEndsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventStationName whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventStationName whereLike($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventStationName whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventStationName whereRoundId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventStationName whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class EventStationName extends BaseEventModel
{
    use Filterable, HasFactory;

    protected $table = 'events_station_names';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gameRound()
    {
        return $this->belongsTo(GameRound::class, 'round_id');
    }
}
