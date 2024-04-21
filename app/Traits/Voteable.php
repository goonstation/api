<?php

namespace App\Traits;

use App\Models\Vote;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Voteable
{
    public function votes(): MorphMany
    {
        return $this->morphMany(Vote::class, 'voteable');
    }

    public function getTotalVotesAttribute()
    {
        return $this->votes()->sum('value');
    }

    public function userVotes()
    {
        return $this->votes()->where('ip', request()->ip());
    }
}
