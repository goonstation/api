<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LatestEventSearchIndex extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'latest_event_search_indexes';

    protected $fillable = [
        'event_type',
        'latest_indexed'
    ];
}
