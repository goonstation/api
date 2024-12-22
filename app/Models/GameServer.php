<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property string $server_id
 * @property string $name
 * @property string $short_name
 * @property string $address
 * @property int $port
 * @property bool $active
 * @property bool $invisible
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $byond_link
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameServer filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameServer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameServer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameServer paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameServer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameServer simplePaginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameServer whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameServer whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameServer whereBeginsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameServer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameServer whereEndsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameServer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameServer whereInvisible($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameServer whereLike($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameServer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameServer wherePort($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameServer whereServerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameServer whereShortName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameServer whereUpdatedAt($value)
 *
 * @property-read \App\Models\GameBuildSetting|null $gameBuildSetting
 *
 * @mixin \Eloquent
 */
class GameServer extends Model
{
    use Filterable, HasFactory;

    protected $appends = ['byond_link'];

    public function getByondLinkAttribute()
    {
        return 'byond://'.$this->address.':'.$this->port;
    }

    public function gameBuildSetting(): HasOne
    {
        return $this->hasOne(GameBuildSetting::class, 'server_id', 'server_id');
    }
}
