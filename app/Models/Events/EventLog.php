<?php

namespace App\Models\Events;

use App\Models\GameRound;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class EventLog extends Model
{
    use Filterable, HasFactory, Searchable;

    protected $table = 'events_logs';

    public function searchableAs(): string
    {
        return 'events_logs';
    }

    public function toSearchableArray()
    {
        return [
            'id' => (int) $this->id,
            'source' => $this->source,
            'message' => $this->message,
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gameRound()
    {
        return $this->belongsTo(GameRound::class, 'round_id');
    }
}
