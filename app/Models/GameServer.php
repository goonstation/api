<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameServer extends Model
{
    use Filterable, HasFactory;

    protected $appends = ['byond_link'];

    public function getByondLinkAttribute()
    {
        return 'byond://' . $this->address . ':' . $this->port;
    }
}
