<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameAdmin extends Model
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
