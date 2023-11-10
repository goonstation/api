<?php

namespace App\Models\Events;

use App\Models\GameRound;
use App\Models\Player;
use Awobaz\Compoships\Compoships;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventAntag extends Model
{
    use Compoships, Filterable, HasFactory;

    protected $table = 'events_antags';

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

    public function objectives()
    {
        return $this->hasMany(
            EventAntagObjective::class,
            ['player_id', 'round_id'],
            ['player_id', 'round_id']
        );
    }

    public function itemPurchases()
    {
        return $this->hasMany(
            EventAntagItemPurchase::class,
            ['player_id', 'round_id'],
            ['player_id', 'round_id']
        );
    }
}
