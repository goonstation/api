<?php

namespace App\Models\Events;

use App\Models\GameRound;
use App\Models\Player;
use App\Traits\HasOpenGraphData;
use App\Traits\Voteable;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $round_id
 * @property int|null $player_id
 * @property string|null $mob_name
 * @property string|null $mob_job
 * @property int|null $x
 * @property int|null $y
 * @property int|null $z
 * @property float|null $bruteloss
 * @property float|null $fireloss
 * @property float|null $toxloss
 * @property float|null $oxyloss
 * @property bool|null $gibbed
 * @property string|null $last_words
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read GameRound $gameRound
 * @property-read mixed $total_votes
 * @property-read Player|null $player
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Vote> $userVotes
 * @property-read int|null $user_votes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Vote> $votes
 * @property-read int|null $votes_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventDeath filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventDeath newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventDeath newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventDeath paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventDeath query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventDeath simplePaginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventDeath whereBeginsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventDeath whereBruteloss($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventDeath whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventDeath whereEndsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventDeath whereFireloss($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventDeath whereGibbed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventDeath whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventDeath whereLastWords($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventDeath whereLike($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventDeath whereMobJob($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventDeath whereMobName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventDeath whereOxyloss($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventDeath wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventDeath whereRoundId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventDeath whereToxloss($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventDeath whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventDeath whereX($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventDeath whereY($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventDeath whereZ($value)
 *
 * @mixin \Eloquent
 */
class EventDeath extends BaseEventModel
{
    use Filterable, HasFactory, HasOpenGraphData, Voteable;

    protected $table = 'events_deaths';

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

    public static function getOpenGraphData(int $id)
    {
        return self::with([
            'gameRound',
            'gameRound.server',
        ])
            ->where('id', $id)
            ->whereRelation('gameRound', 'ended_at', '!=', null)
            ->firstOrFail();
    }
}
