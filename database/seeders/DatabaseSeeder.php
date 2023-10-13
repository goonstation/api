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
                'address' => 'goon1.goonhub.com',
                'port' => 26100,
                'active' => true,
                'invisible' => false,
            ],
            [
                'server_id' => 'main2',
                'name' => 'Goonstation 2 Classic: Bombini',
                'address' => 'goon2.goonhub.com',
                'port' => 26200,
                'active' => false,
                'invisible' => false,
            ],
            [
                'server_id' => 'main3',
                'name' => 'Goonstation 3 Roleplay: Morty',
                'address' => 'goon3.goonhub.com',
                'port' => 26300,
                'active' => true,
                'invisible' => false,
            ],
            [
                'server_id' => 'main4',
                'name' => 'Goonstation 4 Roleplay: Sylvester',
                'address' => 'goon4.goonhub.com',
                'port' => 26400,
                'active' => true,
                'invisible' => false,
            ],
            [
                'server_id' => 'main5',
                'name' => 'Goonstation 5 Event: Rocko',
                'address' => 'goon5.goonhub.com',
                'port' => 26500,
                'active' => true,
                'invisible' => false,
            ],
            [
                'server_id' => 'dev',
                'name' => 'Goonstation Development',
                'address' => 'goondev.goonhub.com',
                'port' => 26900,
                'active' => true,
                'invisible' => true,
            ],
            [
                'server_id' => 'local',
                'name' => 'Goonstation Local',
                'address' => '127.0.0.1',
                'port' => 4999,
                'active' => false,
                'invisible' => true,
            ],
        ]);

        Map::insert([
            ['uri' => 'atlas', 'name' => 'Atlas', 'active' => true],
            ['uri' => 'cogmap', 'name' => 'Cogmap', 'active' => true],
            ['uri' => 'cogmap2', 'name' => 'Cogmap 2', 'active' => true],
            ['uri' => 'clarion', 'name' => 'Clarion', 'active' => true],
            ['uri' => 'destiny', 'name' => 'Destiny', 'active' => true],
            ['uri' => 'donut2', 'name' => 'Donut 2', 'active' => true],
            ['uri' => 'donut3', 'name' => 'Donut 3', 'active' => true],
            ['uri' => 'kondaru', 'name' => 'Kondaru', 'active' => true],
            ['uri' => 'nadir', 'name' => 'Nadir', 'active' => true],
            ['uri' => 'oshan', 'name' => 'Oshan', 'active' => true],
            ['uri' => 'podwars', 'name' => 'Pod Wars', 'active' => true],
        ]);

        // $this->call([
        //     PlayerSeeder::class
        // ]);
    }
}
