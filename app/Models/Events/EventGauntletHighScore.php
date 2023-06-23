<?php

namespace App\Models\Events;

use App\Models\GameRound;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventGauntletHighScore extends Model
{
    use HasFactory;

    protected $table = 'events_gauntlet_high_scores';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gameRound()
    {
        return $this->belongsTo(GameRound::class, 'round_id');
    }
}
