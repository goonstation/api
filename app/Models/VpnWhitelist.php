<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $game_admin_id
 * @property string $ckey
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\GameAdmin $gameAdmin
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VpnWhitelist filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VpnWhitelist newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VpnWhitelist newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VpnWhitelist paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VpnWhitelist query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VpnWhitelist simplePaginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VpnWhitelist whereBeginsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VpnWhitelist whereCkey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VpnWhitelist whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VpnWhitelist whereEndsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VpnWhitelist whereGameAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VpnWhitelist whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VpnWhitelist whereLike($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VpnWhitelist whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class VpnWhitelist extends BaseModel
{
    use Filterable, HasFactory;

    protected $table = 'vpn_whitelist';

    protected $fillable = [
        'ckey',
        'game_admin_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gameAdmin()
    {
        return $this->belongsTo(GameAdmin::class);
    }
}
