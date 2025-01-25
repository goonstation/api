<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int|null $game_admin_id
 * @property string $question
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property bool $multiple_choice
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $servers
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PollAnswer> $answers
 * @property-read int|null $answers_count
 * @property-read \App\Models\GameAdmin|null $gameAdmin
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PollOption> $options
 * @property-read int|null $options_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poll filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poll newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poll newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poll paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poll query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poll simplePaginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poll whereBeginsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poll whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poll whereEndsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poll whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poll whereGameAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poll whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poll whereLike($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poll whereMultipleChoice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poll whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poll whereServers($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Poll whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Poll extends BaseModel
{
    use Filterable, HasFactory;

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gameAdmin()
    {
        return $this->belongsTo(GameAdmin::class, 'game_admin_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function options()
    {
        return $this->hasMany(PollOption::class, 'poll_id');
    }

    public function answers()
    {
        return $this->hasManyThrough(PollAnswer::class, PollOption::class);
    }
}
