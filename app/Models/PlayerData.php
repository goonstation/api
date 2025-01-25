<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $player_id
 * @property string $key
 * @property string|null $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Player $player
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerData newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerData newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerData query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerData whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerData whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerData whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerData wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerData whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerData whereValue($value)
 *
 * @mixin \Eloquent
 */
class PlayerData extends BaseModel
{
    use HasFactory;

    protected $table = 'player_data';

    protected $fillable = [
        'player_id',
        'key',
        'value',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id');
    }
}
