<?php

namespace App\Models\Events;

use App\Models\GameRound;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $round_id
 * @property string|null $names
 * @property int|null $score
 * @property int|null $highest_wave
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read GameRound $gameRound
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventGauntletHighScore filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventGauntletHighScore newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventGauntletHighScore newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventGauntletHighScore paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventGauntletHighScore query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventGauntletHighScore simplePaginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventGauntletHighScore whereBeginsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventGauntletHighScore whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventGauntletHighScore whereEndsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventGauntletHighScore whereHighestWave($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventGauntletHighScore whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventGauntletHighScore whereLike($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventGauntletHighScore whereNames($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventGauntletHighScore whereRoundId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventGauntletHighScore whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventGauntletHighScore whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class EventGauntletHighScore extends Model
{
    use Filterable, HasFactory;

    protected $table = 'events_gauntlet_high_scores';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gameRound()
    {
        return $this->belongsTo(GameRound::class, 'round_id');
    }
}
