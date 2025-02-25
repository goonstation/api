<?php

namespace App\Models;

use App\Observers\MapObserver;
use App\Traits\HasOpenGraphData;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;

/**
 * @property int $id
 * @property string $map_id
 * @property string $name
 * @property bool $active
 * @property bool $is_layer
 * @property int $tile_width
 * @property int $tile_height
 * @property string|null $last_built_at
 * @property int|null $last_built_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property bool $admin_only
 * @property-read \App\Models\GameAdmin|null $gameAdmin
 * @property-read \App\Models\GameRound|null $latestGameRound
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Map> $layers
 * @property-read int|null $layers_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Map filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Map newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Map newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Map paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Map query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Map simplePaginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Map whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Map whereAdminOnly($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Map whereBeginsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Map whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Map whereEndsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Map whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Map whereIsLayer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Map whereLastBuiltAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Map whereLastBuiltBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Map whereLike($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Map whereMapId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Map whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Map whereTileHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Map whereTileWidth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Map whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
#[ObservedBy([MapObserver::class])]
class Map extends BaseModel
{
    use Filterable, HasFactory, HasOpenGraphData, Notifiable;

    const PUBLIC_ROOT = 'app/public/maps';

    const PRIVATE_ROOT = 'app/private-maps';

    protected $casts = [
        'last_updated_at' => 'datetime',
    ];

    public function latestGameRound(): HasOne
    {
        return $this->hasOne(GameRound::class, 'map', 'map_id')->latest();
    }

    public function gameAdmin(): BelongsTo
    {
        return $this->belongsTo(GameAdmin::class, 'last_built_by');
    }

    public function layers(): HasManyThrough
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
        $map->setAttribute('thumb_path', storage_path('app/public/maps/'.strtolower($map->map_id).'/thumb.png'));

        return $map;
    }
}
