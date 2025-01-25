<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int|null $player_id
 * @property string|null $ckey
 * @property int|null $game_admin_id
 * @property string|null $server_id
 * @property int|null $round_id
 * @property string $note
 * @property string|null $legacy_data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\GameAdmin|null $gameAdmin
 * @property-read \App\Models\GameRound|null $gameRound
 * @property-read \App\Models\GameServer|null $gameServer
 * @property-read \App\Models\Player|null $player
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerNote filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerNote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerNote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerNote paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerNote query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerNote simplePaginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerNote whereBeginsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerNote whereCkey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerNote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerNote whereEndsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerNote whereGameAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerNote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerNote whereLegacyData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerNote whereLike($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerNote whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerNote wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerNote whereRoundId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerNote whereServerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerNote whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class PlayerNote extends BaseModel
{
    use Filterable, HasFactory;

    protected $fillable = [
        'game_admin_id',
        'player_id',
        'server_id',
        'ckey',
        'note',
    ];

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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gameAdmin()
    {
        return $this->belongsTo(GameAdmin::class, 'game_admin_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gameServer()
    {
        return $this->belongsTo(GameServer::class, 'server_id', 'server_id');
    }
}
