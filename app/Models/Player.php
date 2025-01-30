<?php

namespace App\Models;

use App\Models\Events\EventDeath;
use App\Traits\HasOpenGraphData;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property string $ckey
 * @property string|null $key
 * @property string|null $byond_join_date
 * @property int|null $byond_major
 * @property int|null $byond_minor
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PlayerConnection> $connections
 * @property-read int|null $connections_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, EventDeath> $deaths
 * @property-read int|null $deaths_count
 * @property-read \App\Models\PlayerConnection|null $firstConnection
 * @property-read mixed $has_imported_medals
 * @property-read \App\Models\PlayerMedalsImported|null $importedMedals
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\JobBan> $jobBans
 * @property-read int|null $job_bans_count
 * @property-read \App\Models\PlayerConnection|null $latestConnection
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PlayerMedal> $medals
 * @property-read int|null $medals_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PlayerNote> $notes
 * @property-read int|null $notes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PlayerParticipation> $participations
 * @property-read int|null $participations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PlayerParticipation> $participationsRp
 * @property-read int|null $participations_rp_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PlayerPlaytime> $playtime
 * @property-read int|null $playtime_count
 * @property-read \App\Models\VpnWhitelist|null $vpnWhitelist
 *
 * @method static \Database\Factories\PlayerFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Player filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Player newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Player newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Player paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Player query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Player simplePaginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Player whereBeginsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Player whereByondJoinDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Player whereByondMajor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Player whereByondMinor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Player whereCkey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Player whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Player whereEndsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Player whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Player whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Player whereLike($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Player whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Player extends BaseModel
{
    use Filterable, HasFactory, HasOpenGraphData;

    protected $fillable = [
        'id',
        'ckey',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function connections()
    {
        return $this->hasMany(PlayerConnection::class, 'player_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function latestConnection()
    {
        return $this->hasOne(PlayerConnection::class, 'player_id')->latestOfMany();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function firstConnection()
    {
        return $this->hasOne(PlayerConnection::class, 'player_id')->oldestOfMany();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function participations()
    {
        return $this->hasMany(PlayerParticipation::class, 'player_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function participationsRp()
    {
        return $this->hasMany(PlayerParticipation::class, 'player_id')
            ->where(function ($q) {
                $q->whereRelation('gameRound', 'rp_mode', true)
                    ->orWhere('legacy_data->rp_mode', 'true');
            });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function playtime()
    {
        return $this->hasMany(PlayerPlaytime::class, 'player_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function deaths()
    {
        return $this->hasMany(EventDeath::class, 'player_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function vpnWhitelist()
    {
        return $this->hasOne(VpnWhitelist::class, 'ckey', 'ckey');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function jobBans()
    {
        return $this->hasMany(JobBan::class, 'ckey', 'ckey');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notes()
    {
        return $this->hasMany(PlayerNote::class, 'player_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    // public function medals()
    // {
    //     return $this->hasManyThrough(
    //         Medal::class,
    //         PlayerMedal::class,
    //         'player_id',
    //         'id'
    //     );
    // }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function medals()
    {
        return $this->hasMany(PlayerMedal::class);
    }

    public function importedMedals()
    {
        return $this->hasOne(PlayerMedalsImported::class);
    }

    /** @return Attribute<bool, never> */
    protected function hasImportedMedals(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->importedMedals()->exists(),
        );
    }

    public static function getOpenGraphData(int $id)
    {
        $player = self::with([
            'playtime',
            'firstConnection',
        ])
            ->withCount([
                'participations',
                'participationsRp',
                'deaths',
            ])
            ->where('id', $id)
            ->firstOrFail();

        $totalSecondsPlayed = 0;
        foreach ($player->playtime as $playtime) {
            $totalSecondsPlayed += $playtime->seconds_played;
        }
        $player->setAttribute('hours_played', $totalSecondsPlayed / 3600);

        return $player;
    }
}
