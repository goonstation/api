<?php

namespace App\Models\Events;

use App\Models\GameRound;
use App\Models\Player;
use Awobaz\Compoships\Compoships;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $round_id
 * @property int|null $player_id
 * @property string|null $objective
 * @property bool|null $success
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read GameRound $gameRound
 * @property-read Player|null $player
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntagObjective filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntagObjective newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntagObjective newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntagObjective paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntagObjective query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntagObjective simplePaginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntagObjective whereBeginsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntagObjective whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntagObjective whereEndsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntagObjective whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntagObjective whereLike($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntagObjective whereObjective($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntagObjective wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntagObjective whereRoundId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntagObjective whereSuccess($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntagObjective whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class EventAntagObjective extends BaseEventModel
{
    use Compoships, Filterable, HasFactory;

    protected $table = 'events_antag_objectives';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gameRound()
    {
        return $this->belongsTo(GameRound::class, 'round_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id');
    }
}
