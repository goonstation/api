<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapLayer extends Model
{
    use HasFactory;

    protected $fillable = [
        'map_id',
        'layer_id',
    ];

    public function map()
    {
        return $this->belongsTo(Map::class, 'map_id');
    }

    public function layer()
    {
        return $this->belongsTo(Map::class, 'layer_id');
    }
}
