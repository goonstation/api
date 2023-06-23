<?php

namespace App\Models\Events;

use App\Models\GameRound;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventBeeSpawn extends Model
{
    use HasFactory;

    protected $table = 'events_bee_spawns';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gameRound()
    {
        return $this->belongsTo(GameRound::class, 'round_id');
    }
}
