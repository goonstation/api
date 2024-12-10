<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $player_id
 * @property int $medal_id
 * @property int|null $round_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\GameRound|null $gameRound
 * @property-read \App\Models\Medal $medal
 * @property-read \App\Models\Player $player
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerMedal filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerMedal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerMedal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerMedal paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerMedal query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerMedal simplePaginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerMedal whereBeginsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerMedal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerMedal whereEndsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerMedal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerMedal whereLike($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerMedal whereMedalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerMedal wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerMedal whereRoundId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerMedal whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class PlayerMedal extends Model
{
    use Filterable, HasFactory;

    protected $fillable = [
        'player_id',
        'medal_id',
        'round_id',
    ];

    protected $hidden = [
        'medal_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function medal()
    {
        return $this->belongsTo(Medal::class, 'medal_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gameRound()
    {
        return $this->belongsTo(GameRound::class, 'round_id');
    }
}
