<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $player_id
 * @property int|null $round_id
 * @property string $antag_role
 * @property bool $late_join
 * @property string|null $weight_exempt
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\GameRound|null $gameRound
 * @property-read \App\Models\Player $player
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerAntag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerAntag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerAntag query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerAntag whereAntagRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerAntag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerAntag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerAntag whereLateJoin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerAntag wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerAntag whereRoundId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerAntag whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerAntag whereWeightExempt($value)
 *
 * @mixin \Eloquent
 */
class PlayerAntag extends BaseModel
{
    use HasFactory;

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
