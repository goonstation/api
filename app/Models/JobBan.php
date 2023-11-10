<?php

namespace App\Models;

use Carbon\Carbon;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobBan extends Model
{
    use Filterable, HasFactory, SoftDeletes;

    protected $dates = ['expires_at'];

    protected $fillable = [
        'server_id',
        'reason',
        'banned_from_job',
        'expires_at',
    ];

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
     * @return Builder
     */
    public static function getValidJobBans(string $ckey, string $job = null, string $serverId = null)
    {
        $query = JobBan::with(['gameAdmin:id,ckey,name'])
            ->where('ckey', $ckey)
            ->where(function (Builder $builder) use ($serverId) {
                // Check if the ban applies to all servers, or the server id we were provided
                $builder->whereNull('server_id')
                    ->orWhere('server_id', $serverId);
            })
            ->where(function (Builder $builder) {
                // Check the ban is permanent, or has yet to expire
                $builder->whereNull('expires_at')
                    ->orWhere('expires_at', '>', Carbon::now()->toDateTimeString());
            });

        if ($job) {
            $query = $query->where('banned_from_job', $job);
        }

        return $query;
    }
}
