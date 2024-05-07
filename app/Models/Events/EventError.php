<?php

namespace App\Models\Events;

use App\Models\GameRound;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventError extends Model
{
    use Filterable, HasFactory;

    protected $table = 'events_errors';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'round_ids' => 'array',
            'server_ids' => 'array',
            'round_error_counts' => 'array',
        ];
    }

    protected function signature(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => md5(
                $attributes['name'].
                $attributes['file'].
                $attributes['line']
            ),
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gameRound()
    {
        return $this->belongsTo(GameRound::class, 'round_id');
    }
}
