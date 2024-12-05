<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $poll_id
 * @property string $option
 * @property int $position
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PollAnswer> $answers
 * @property-read int|null $answers_count
 * @property-read \App\Models\Poll $poll
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PollOption newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PollOption newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PollOption query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PollOption whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PollOption whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PollOption whereOption($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PollOption wherePollId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PollOption wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PollOption whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class PollOption extends Model
{
    use HasFactory;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function poll()
    {
        return $this->belongsTo(Poll::class, 'poll_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answers()
    {
        return $this->hasMany(PollAnswer::class, 'poll_option_id');
    }
}
