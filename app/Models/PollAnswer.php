<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $poll_option_id
 * @property int $player_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\PollOption $option
 * @property-read \App\Models\Player $player
 * @property-read \App\Models\Poll|null $poll
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PollAnswer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PollAnswer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PollAnswer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PollAnswer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PollAnswer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PollAnswer wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PollAnswer wherePollOptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PollAnswer whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class PollAnswer extends BaseModel
{
    use HasFactory;

    public function poll()
    {
        return $this->hasOneThrough(Poll::class, PollOption::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function option()
    {
        return $this->belongsTo(PollOption::class, 'poll_option_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id');
    }
}
