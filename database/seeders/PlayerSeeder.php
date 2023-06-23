<?php

namespace Database\Seeders;

use App\Models\Player;
use App\Models\PlayerConnection;
use Illuminate\Database\Seeder;

class PlayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Player::factory()
            ->count(10)
            ->sequence(fn ($sequence) => [
                'ckey' => 'player'.$sequence->index,
                'key' => 'Player '.$sequence->index,
            ])
            ->has(PlayerConnection::factory()->count(3), 'connections')
            ->create();
    }
}
