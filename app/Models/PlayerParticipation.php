<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $player_id
 * @property int|null $round_id
 * @property string|null $job
 * @property string|null $legacy_data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\GameRound|null $gameRound
 * @property-read \App\Models\Player $player
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerParticipation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerParticipation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerParticipation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerParticipation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerParticipation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerParticipation whereJob($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerParticipation whereLegacyData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerParticipation wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerParticipation whereRoundId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerParticipation whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class PlayerParticipation extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'created_at',
        'updated_at',
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
}
