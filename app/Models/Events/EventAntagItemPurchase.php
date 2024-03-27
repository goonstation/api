<?php

namespace App\Models\Events;

use App\Models\GameRound;
use App\Models\Player;
use Awobaz\Compoships\Compoships;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventAntagItemPurchase extends Model
{
    use Compoships, Filterable, HasFactory;

    protected $table = 'events_antag_item_purchases';

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
