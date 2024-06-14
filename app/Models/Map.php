<?php

namespace App\Models;

use App\Traits\HasOpenGraphData;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Map extends Model
{
    use Filterable, HasFactory, HasOpenGraphData;

    protected $casts = [
        'last_updated_at' => 'datetime',
    ];

    public function latestGameRound()
    {
        return $this->hasOne(GameRound::class, 'map', 'map_id')->latest();
    }

    public function gameAdmin()
    {
        return $this->belongsTo(GameAdmin::class, 'last_built_by');
    }

    public function layers()
    {
        return $this->hasManyThrough(Map::class, MapLayer::class, 'map_id', 'id', 'id', 'layer_id');
    }

    public static function getOpenGraphData(int $id)
    {
        $map = self::where('id', $id)
            ->where('active', true)
            ->where('is_layer', false)
            ->where('admin_only', false)
            ->firstOrFail();
        $map->thumb_path = storage_path('app/public/maps/'.strtolower($map->map_id).'/thumb.png');

        return $map;
    }
}
