<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Map extends Model
{
    use HasFactory, Filterable;

    protected $dates = ['last_updated_at'];

    public function latestGameRound()
    {
        return $this->hasOne(GameRound::class, 'map', 'map_id')->latest();
    }

    public function gameAdmin()
    {
        return $this->belongsTo(GameAdmin::class, 'last_built_by');
    }

}
