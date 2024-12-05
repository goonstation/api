<?php

namespace App\Models\Events;

use App\Models\GameRound;
use App\Models\Player;
use App\Traits\HasOpenGraphData;
use Awobaz\Compoships\Compoships;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $round_id
 * @property int|null $player_id
 * @property string|null $mob_name
 * @property string|null $mob_job
 * @property string|null $traitor_type
 * @property string|null $special
 * @property string|null $late_joiner
 * @property bool|null $success
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read GameRound $gameRound
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Events\EventAntagItemPurchase> $itemPurchases
 * @property-read int|null $item_purchases_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Events\EventAntagObjective> $objectives
 * @property-read int|null $objectives_count
 * @property-read Player|null $player
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntag filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntag paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntag query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntag simplePaginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntag whereBeginsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntag whereEndsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntag whereLateJoiner($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntag whereLike($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntag whereMobJob($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntag whereMobName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntag wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntag whereRoundId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntag whereSpecial($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntag whereSuccess($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntag whereTraitorType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAntag whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class EventAntag extends Model
{
    use Compoships, Filterable, HasFactory, HasOpenGraphData;

    protected $table = 'events_antags';

    public function gameRound(): BelongsTo
    {
        return $this->belongsTo(GameRound::class, 'round_id');
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'player_id');
    }

    public function objectives(): HasMany
    {
        return $this->hasMany(
            EventAntagObjective::class,
            ['player_id', 'round_id'],
            ['player_id', 'round_id']
        );
    }

    public function itemPurchases(): HasMany
    {
        return $this->hasMany(
            EventAntagItemPurchase::class,
            ['player_id', 'round_id'],
            ['player_id', 'round_id']
        );
    }

    public static function getOpenGraphData(int $id)
    {
        return self::with([
            'gameRound',
            'gameRound.server',
            'objectives',
            'itemPurchases',
        ])
            ->where('id', $id)
            ->whereRelation('gameRound', 'ended_at', '!=', null)
            ->firstOrFail();
    }
}
