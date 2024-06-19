<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medal extends Model
{
    use Filterable, HasFactory;

    protected $fillable = [
        'title',
        'description',
        'hidden',
    ];

    protected $hidden = [
        'id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    // public function players()
    // {
    //     return $this->hasManyThrough(Player::class, PlayerMedal::class);
    // }

    public function earned()
    {
        return $this->hasMany(PlayerMedal::class);
    }
}
