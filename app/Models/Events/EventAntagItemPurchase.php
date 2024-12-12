<?php

namespace App\Models\Events;

use App\Models\GameRound;
use App\Models\Player;
use Awobaz\Compoships\Compoships;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $round_id
 * @property int|null $player_id
 * @property string|null $mob_name
 * @property string|null $mob_job
 * @property int|null $x
 * @property int|null $y
 * @property int|null $z
 * @property string|null $item
 * @property int|null $cost
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read GameRound $gameRound
 * @property-read Player|null $player
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntagItemPurchase filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntagItemPurchase newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntagItemPurchase newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntagItemPurchase paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntagItemPurchase query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntagItemPurchase simplePaginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntagItemPurchase whereBeginsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntagItemPurchase whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntagItemPurchase whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntagItemPurchase whereEndsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntagItemPurchase whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntagItemPurchase whereItem($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntagItemPurchase whereLike($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntagItemPurchase whereMobJob($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntagItemPurchase whereMobName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntagItemPurchase wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntagItemPurchase whereRoundId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntagItemPurchase whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntagItemPurchase whereX($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntagItemPurchase whereY($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntagItemPurchase whereZ($value)
 *
 * @mixin \Eloquent
 */
class EventAntagItemPurchase extends Model
{
    use Compoships, Filterable, HasFactory;

    protected $table = 'events_antag_item_purchases';

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
