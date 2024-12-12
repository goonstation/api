<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $player_id
 * @property int $seconds_played
 * @property string $server_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Player $player
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerPlaytime newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerPlaytime newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerPlaytime query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerPlaytime whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerPlaytime wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerPlaytime whereSecondsPlayed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerPlaytime whereServerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerPlaytime whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class PlayerPlaytime extends Model
{
    use HasFactory;

    protected $table = 'player_playtime';

    protected $fillable = [
        'id',
        'seconds_played',
        'server_id',
        'created_at',
        'updated_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id');
    }
}
