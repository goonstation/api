<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlobalStat extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'stats',
    ];
}
