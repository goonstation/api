<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    use Filterable, HasFactory;

    protected $dates = ['expires_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gameAdmin()
    {
        return $this->belongsTo(GameAdmin::class, 'game_admin_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function options()
    {
        return $this->hasMany(PollOption::class, 'poll_id');
    }

    public function answers()
    {
        return $this->hasManyThrough(PollAnswer::class, PollOption::class);
    }
}
