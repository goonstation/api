<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $map_id
 * @property int $layer_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Map $layer
 * @property-read \App\Models\Map $map
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MapLayer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MapLayer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MapLayer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MapLayer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MapLayer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MapLayer whereLayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MapLayer whereMapId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MapLayer whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class MapLayer extends BaseModel
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
