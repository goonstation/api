<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $server_id
 * @property string $branch
 * @property int $byond_major
 * @property int $byond_minor
 * @property string $rustg_version
 * @property bool $rp_mode
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $map_id
 * @property-read \App\Models\GameServer $gameServer
 * @property-read \App\Models\Map|null $map
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildSetting filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildSetting paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildSetting simplePaginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildSetting whereBeginsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildSetting whereBranch($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildSetting whereByondMajor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildSetting whereByondMinor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildSetting whereEndsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildSetting whereLike($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildSetting whereMapId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildSetting whereRpMode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildSetting whereRustgVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildSetting whereServerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildSetting whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class GameBuildSetting extends Model
{
    use Filterable, HasFactory;

    public function gameServer(): BelongsTo
    {
        return $this->belongsTo(GameServer::class, 'server_id', 'server_id');
    }

    public function map(): BelongsTo
    {
        return $this->belongsTo(Map::class, 'map_id', 'map_id');
    }
}
