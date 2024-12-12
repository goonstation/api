<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int|null $player_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Player|null $player
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerMedalsImported newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerMedalsImported newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerMedalsImported query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerMedalsImported whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerMedalsImported whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerMedalsImported wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerMedalsImported whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class PlayerMedalsImported extends Model
{
    use HasFactory;

    protected $table = 'player_medals_imported';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id');
    }
}
