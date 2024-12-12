<?php

namespace App\Models\Events;

use App\Models\GameRound;
use App\Models\Player;
use App\Traits\HasOpenGraphData;
use App\Traits\Voteable;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $round_id
 * @property int|null $player_id
 * @property string|null $target
 * @property string|null $reason
 * @property string|null $issuer
 * @property string|null $issuer_job
 * @property string|null $issuer_ckey
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read GameRound $gameRound
 * @property-read mixed $total_votes
 * @property-read Player|null $player
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Vote> $userVotes
 * @property-read int|null $user_votes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Vote> $votes
 * @property-read int|null $votes_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTicket filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTicket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTicket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTicket paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTicket query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTicket simplePaginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTicket whereBeginsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTicket whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTicket whereEndsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTicket whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTicket whereIssuer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTicket whereIssuerCkey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTicket whereIssuerJob($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTicket whereLike($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTicket wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTicket whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTicket whereRoundId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTicket whereTarget($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventTicket whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class EventTicket extends Model
{
    use Filterable, HasFactory, HasOpenGraphData, Voteable;

    protected $table = 'events_tickets';

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
