<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property string $ckey
 * @property string|null $name
 * @property int|null $rank_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ban> $bans
 * @property-read int|null $bans_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\JobBan> $jobBans
 * @property-read int|null $job_bans_count
 * @property-read \App\Models\GameAdminRank|null $rank
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameAdmin filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameAdmin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameAdmin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameAdmin paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameAdmin query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameAdmin simplePaginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameAdmin whereBeginsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameAdmin whereCkey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameAdmin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameAdmin whereEndsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameAdmin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameAdmin whereLike($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameAdmin whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameAdmin whereRankId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameAdmin whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class GameAdmin extends BaseModel
{
    use Filterable, HasFactory;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function rank()
    {
        return $this->hasOne(GameAdminRank::class, 'id', 'rank_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bans()
    {
        return $this->hasMany(Ban::class, 'game_admin_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function jobBans()
    {
        return $this->hasMany(JobBan::class, 'game_admin_id');
    }
}
