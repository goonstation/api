<?php

namespace App\Models\Events;

use App\Models\GameRound;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $round_id
 * @property string|null $name
 * @property string|null $file
 * @property int|null $line
 * @property string|null $desc
 * @property string|null $user
 * @property string|null $user_ckey
 * @property bool $invalid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read GameRound $gameRound
 * @property-read mixed $signature
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventError filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventError newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventError newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventError paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventError query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventError simplePaginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventError whereBeginsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventError whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventError whereDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventError whereEndsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventError whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventError whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventError whereInvalid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventError whereLike($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventError whereLine($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventError whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventError whereRoundId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventError whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventError whereUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventError whereUserCkey($value)
 *
 * @mixin \Eloquent
 */
class EventError extends Model
{
    use Filterable, HasFactory;

    protected $table = 'events_errors';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'round_ids' => 'array',
            'server_ids' => 'array',
            'round_error_counts' => 'array',
            'overview_count' => 'int',
            'overview_round_count' => 'int',
        ];
    }

    protected function signature(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => md5(
                $attributes['name'].
                $attributes['file'].
                $attributes['line']
            ),
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gameRound()
    {
        return $this->belongsTo(GameRound::class, 'round_id');
    }
}
