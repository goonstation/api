<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameAdminRank extends Model
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
