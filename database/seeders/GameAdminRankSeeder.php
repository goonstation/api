<?php

namespace Database\Seeders;

use App\Models\GameAdminRank;
use Kdabrow\SeederOnce\SeederOnce;

class GameAdminRankSeeder extends SeederOnce
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GameAdminRank::insert([
            ['rank' => 'Host'],
            ['rank' => 'Coder'],
            ['rank' => 'Administrator'],
            ['rank' => 'Inactive'],
            ['rank' => 'Bot'],
        ]);
    }
}
