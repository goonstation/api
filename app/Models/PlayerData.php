<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerData extends Model
{
    use HasFactory;

    protected $table = 'player_data';

    protected $fillable = [
        'player_id',
        'key',
        'value',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id');
    }
}
