<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $player_id
 * @property string $name
 * @property string|null $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Player $player
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerSave newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerSave newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerSave query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerSave whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerSave whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerSave whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerSave whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerSave wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerSave whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class PlayerSave extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'name',
        'data',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id');
    }
}
