<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\GameServer;
use App\Models\Map;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // User::factory()->create([
        //     'name' => 'Dev User',
        //     'email' => 'dev@example.com',
        // ]);

        GameServer::insert([
            [
                'server_id' => 'main1',
                'name' => 'Goonstation 1 Classic: Heisenbee',
                'short_name' => 'Goon 1',
                'address' => 'goon1.goonhub.com',
                'port' => 26100,
                'active' => true,
                'invisible' => false,
            ],
            [
                'server_id' => 'main2',
                'name' => 'Goonstation 2 Classic: Bombini',
                'short_name' => 'Goon 2',
                'address' => 'goon2.goonhub.com',
                'port' => 26200,
                'active' => false,
                'invisible' => false,
            ],
            [
                'server_id' => 'main3',
                'name' => 'Goonstation 3 Roleplay: Morty',
                'short_name' => 'Goon 3 RP',
                'address' => 'goon3.goonhub.com',
                'port' => 26300,
                'active' => true,
                'invisible' => false,
            ],
            [
                'server_id' => 'main4',
                'name' => 'Goonstation 4 Roleplay: Sylvester',
                'short_name' => 'Goon 4 RP',
                'address' => 'goon4.goonhub.com',
                'port' => 26400,
                'active' => true,
                'invisible' => false,
            ],
            [
                'server_id' => 'main5',
                'name' => 'Goonstation 5 Event: Rocko',
                'short_name' => 'Goon 5 Event',
                'address' => 'goon5.goonhub.com',
                'port' => 26500,
                'active' => true,
                'invisible' => false,
            ],
            [
                'server_id' => 'dev',
                'name' => 'Goonstation Development',
                'short_name' => 'Goon Dev',
                'address' => 'goondev.goonhub.com',
                'port' => 26900,
                'active' => true,
                'invisible' => true,
            ],
            [
                'server_id' => 'local',
                'name' => 'Goonstation Local',
                'short_name' => 'Goon Local',
                'address' => '127.0.0.1',
                'port' => 4999,
                'active' => false,
                'invisible' => true,
            ],
        ]);

        Map::insert([
            ['map_id' => 'ATLAS', 'name' => 'Atlas', 'active' => true],
            ['map_id' => 'COGMAP', 'name' => 'Cogmap', 'active' => true],
            ['map_id' => 'COGMAP2', 'name' => 'Cogmap 2', 'active' => true],
            ['map_id' => 'CLARION', 'name' => 'Clarion', 'active' => true],
            ['map_id' => 'DEBRIS', 'name' => 'Debris', 'active' => false],
            ['map_id' => 'DESTINY', 'name' => 'Destiny', 'active' => true],
            ['map_id' => 'DONUT2', 'name' => 'Donut 2', 'active' => true],
            ['map_id' => 'DONUT3', 'name' => 'Donut 3', 'active' => true],
            ['map_id' => 'KONDARU', 'name' => 'Kondaru', 'active' => true],
            ['map_id' => 'NADIR', 'name' => 'Nadir', 'active' => true],
            ['map_id' => 'OSHAN', 'name' => 'Oshan', 'active' => true],
            ['map_id' => 'PODWARS', 'name' => 'Pod Wars', 'active' => true],
        ]);

        // $this->call([
        //     PlayerSeeder::class
        // ]);
    }
}
