<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $player_id
 * @property int|null $round_id
 * @property string $ip
 * @property string $comp_id
 * @property string|null $legacy_data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $country
 * @property string|null $country_iso
 * @property-read \App\Models\GameRound|null $gameRound
 * @property-read \App\Models\Player $player
 *
 * @method static \Database\Factories\PlayerConnectionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerConnection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerConnection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerConnection query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerConnection whereCompId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerConnection whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerConnection whereCountryIso($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerConnection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerConnection whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerConnection whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerConnection whereLegacyData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerConnection wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerConnection whereRoundId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerConnection whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class PlayerConnection extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'ip',
        'comp_id',
        'country',
        'country_iso',
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
