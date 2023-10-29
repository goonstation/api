<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Map extends Model
{
    use HasFactory;

    protected $dates = ['last_updated_at'];

    public function latestGameRound()
    {
        return $this->hasOne(GameRound::class, 'map', 'map_id')->latest();
    }
}
