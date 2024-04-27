<?php

namespace App\Models\Events;

use App\Models\GameRound;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class EventError extends Model
{
    use Filterable, HasFactory, Searchable;

    protected $table = 'events_errors';

    public function toSearchableArray()
    {
        return [
            'id' => (int) $this->id,
            'round_id' => (int) $this->round_id,
            'name' => $this->name,
            'file' => $this->file,
            'desc' => $this->desc,
            'user' => $this->user,
            'user_ckey' => $this->user_ckey,
            'created_at' => (int) $this->created_at->getTimestampMs(),
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
