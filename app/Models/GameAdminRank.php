<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property string $rank
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\GameAdmin> $admins
 * @property-read int|null $admins_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Audit> $audits
 * @property-read int|null $audits_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameAdminRank filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameAdminRank newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameAdminRank newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameAdminRank paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameAdminRank query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameAdminRank simplePaginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameAdminRank whereBeginsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameAdminRank whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameAdminRank whereEndsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameAdminRank whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameAdminRank whereLike($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameAdminRank whereRank($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameAdminRank whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class GameAdminRank extends BaseModel
{
    use Filterable, HasFactory;

    protected $fillable = [
        'rank',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function admins()
    {
        return $this->hasMany(GameAdmin::class, 'rank_id');
    }
}
