<?php

namespace App\Models\Events;

use App\Models\GameRound;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventStationName extends Model
{
    use HasFactory;

    protected $table = 'events_station_names';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gameRound()
    {
        return $this->belongsTo(GameRound::class, 'round_id');
    }

    public function scopeLatestStationName($query)
    {
        $query->latest();
    }
}
