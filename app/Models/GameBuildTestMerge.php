<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read \App\Models\GameAdmin|null $addedBy
 * @property-read \App\Models\GameServer|null $gameServer
 * @property-read \App\Models\GameAdmin|null $updatedBy
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildTestMerge newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildTestMerge newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildTestMerge query()
 *
 * @property int $id
 * @property int $pr_id
 * @property string $server_id
 * @property int|null $added_by
 * @property int|null $updated_by
 * @property string|null $commit
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildTestMerge whereAddedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildTestMerge whereCommit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildTestMerge whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildTestMerge whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildTestMerge wherePrId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildTestMerge whereServerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildTestMerge whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildTestMerge whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildTestMerge filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildTestMerge paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildTestMerge simplePaginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildTestMerge whereBeginsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildTestMerge whereEndsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GameBuildTestMerge whereLike($column, $value, $boolean = 'and')
 *
 * @mixin \Eloquent
 */
class GameBuildTestMerge extends Model
{
    use Filterable, HasFactory;

    protected $table = 'game_build_test_merges';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gameServer()
    {
        return $this->belongsTo(GameServer::class, 'server_id', 'server_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function addedBy()
    {
        return $this->belongsTo(GameAdmin::class, 'added_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function updatedBy()
    {
        return $this->belongsTo(GameAdmin::class, 'updated_by');
    }
}
