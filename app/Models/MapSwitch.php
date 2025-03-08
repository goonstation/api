<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int|null $game_admin_id
 * @property int|null $round_id
 * @property string|null $server_id
 * @property string $map
 * @property int $votes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\GameAdmin|null $gameAdmin
 * @property-read \App\Models\GameRound|null $gameRound
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MapSwitch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MapSwitch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MapSwitch query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MapSwitch whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MapSwitch whereGameAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MapSwitch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MapSwitch whereMap($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MapSwitch whereRoundId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MapSwitch whereServerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MapSwitch whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MapSwitch whereVotes($value)
 *
 * @mixin \Eloquent
 */
class MapSwitch extends BaseModel
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
    public function gameAdmin()
    {
        return $this->belongsTo(GameAdmin::class, 'game_admin_id');
    }
}
