<?php

namespace App\Models\Events;

use App\Models\BaseModel;
use App\Models\GameRound;
use App\Models\Player;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $round_id
 * @property int|null $player_id
 * @property string|null $module
 * @property string|null $borg_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read GameRound $gameRound
 * @property-read Player|null $player
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventCyborgModuleSelection filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventCyborgModuleSelection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventCyborgModuleSelection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventCyborgModuleSelection paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventCyborgModuleSelection query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventCyborgModuleSelection simplePaginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventCyborgModuleSelection whereBeginsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventCyborgModuleSelection whereBorgType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventCyborgModuleSelection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventCyborgModuleSelection whereEndsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventCyborgModuleSelection whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventCyborgModuleSelection whereLike($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventCyborgModuleSelection whereModule($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventCyborgModuleSelection wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventCyborgModuleSelection whereRoundId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventCyborgModuleSelection whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class EventCyborgModuleSelection extends BaseModel
{
    use Filterable, HasFactory;

    protected $table = 'events_cyborg_module_selections';

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
