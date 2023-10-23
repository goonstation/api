<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VpnCheck extends Model
{
    use HasFactory;

    protected $fillable = [
        'round_id',
        'ip',
        'service',
        'error',
        'response',
    ];
}
