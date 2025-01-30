<?php

namespace App\Models;

use Carbon\Carbon;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int|null $round_id
 * @property int|null $game_admin_id
 * @property string|null $server_id
 * @property string $reason
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property bool $requires_appeal
 * @property int|null $deleted_by
 * @property-read \App\Models\GameAdmin|null $deletedByGameAdmin
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BanDetail> $details
 * @property-read int|null $details_count
 * @property-read \App\Models\GameAdmin|null $gameAdmin
 * @property-read \App\Models\GameRound|null $gameRound
 * @property-read \App\Models\GameServer|null $gameServer
 * @property-read mixed $active
 * @property-read mixed $duration
 * @property-read mixed $duration_human
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BanDetail> $inactiveDetails
 * @property-read int|null $inactive_details_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PlayerNote> $notes
 * @property-read int|null $notes_count
 * @property-read \App\Models\BanDetail|null $originalBanDetail
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ban filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ban newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ban newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ban onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ban paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ban query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ban simplePaginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ban whereBeginsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ban whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ban whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ban whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ban whereEndsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ban whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ban whereGameAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ban whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ban whereLike($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ban whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ban whereRequiresAppeal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ban whereRoundId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ban whereServerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ban whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ban withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ban withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Ban extends BaseModel
{
    use Filterable, HasFactory, SoftDeletes;

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    protected $fillable = [
        'round_id',
        'game_admin_id',
        'server_id',
        'reason',
        'expires_at',
    ];

    protected $appends = ['duration', 'duration_human', 'active'];

    public function getDurationAttribute()
    {
        $now = Carbon::now();
        if (! $this->expires_at || $now->isAfter($this->expires_at)) {
            return 0;
        }

        return $now->diffInSeconds($this->expires_at);
    }

    public function getDurationHumanAttribute()
    {
        if (! $this->expires_at) {
            return null;
        }

        return $this->expires_at->longAbsoluteDiffForHumans(parts: 99);
    }

    public function getActiveAttribute()
    {
        $now = Carbon::now();
        $isExpired = $this->expires_at ? $now->isAfter($this->expires_at) : false;

        return ! $isExpired && is_null($this->deleted_at);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gameRound()
    {
        return $this->belongsTo(GameRound::class, 'round_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gameAdmin()
    {
        return $this->belongsTo(GameAdmin::class, 'game_admin_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gameServer()
    {
        return $this->belongsTo(GameServer::class, 'server_id', 'server_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function details()
    {
        return $this->hasMany(BanDetail::class, 'ban_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inactiveDetails()
    {
        return $this->hasMany(BanDetail::class, 'ban_id')->withTrashed()->whereNotNull('deleted_at');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function originalBanDetail()
    {
        return $this->hasOne(BanDetail::class, 'ban_id')->withTrashed()->oldest();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notes()
    {
        return $this->hasMany(PlayerNote::class, 'ban_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function deletedByGameAdmin()
    {
        return $this->belongsTo(GameAdmin::class, 'deleted_by');
    }
}
