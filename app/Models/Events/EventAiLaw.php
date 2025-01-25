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
 * @property string|null $ai_name
 * @property int|null $law_number
 * @property string|null $law_text
 * @property string|null $uploader_name
 * @property string|null $uploader_job
 * @property string|null $uploader_ckey
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read GameRound $gameRound
 * @property-read Player|null $player
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAiLaw filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAiLaw newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAiLaw newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAiLaw paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAiLaw query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAiLaw simplePaginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAiLaw whereAiName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAiLaw whereBeginsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAiLaw whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAiLaw whereEndsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAiLaw whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAiLaw whereLawNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAiLaw whereLawText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAiLaw whereLike($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAiLaw wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAiLaw whereRoundId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAiLaw whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAiLaw whereUploaderCkey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAiLaw whereUploaderJob($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventAiLaw whereUploaderName($value)
 *
 * @mixin \Eloquent
 */
class EventAiLaw extends BaseModel
{
    use Filterable, HasFactory;

    protected $table = 'events_ai_laws';

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
