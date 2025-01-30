<?php

namespace App\Models\Events;

use App\Models\BaseModel;
use App\Models\GameRound;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $round_id
 * @property string|null $type
 * @property string|null $source
 * @property string|null $message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read GameRound $gameRound
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventLog filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventLog paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventLog simplePaginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventLog whereBeginsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventLog whereEndsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventLog whereLike($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventLog whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventLog whereRoundId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventLog whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventLog whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventLog whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class EventLog extends BaseModel
{
    use Filterable, HasFactory;

    protected $table = 'events_logs';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gameRound()
    {
        return $this->belongsTo(GameRound::class, 'round_id');
    }
}
