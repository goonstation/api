<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\GameAdminRank;
use App\Models\GameServer;
use App\Models\Map;
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
        GameAdminRank::insert([
            ['rank' => 'Host'],
            ['rank' => 'Coder'],
            ['rank' => 'Administrator'],
            ['rank' => 'Inactive'],
            ['rank' => 'Bot'],
        ]);

        GameServer::insert([
            [
                'server_id' => 'streamer1',
                'name' => 'Goonstation Nightshade 1',
                'short_name' => 'Nightshade 1',
                'address' => 'tomato1.goonhub.com',
                'port' => 27111,
                'active' => true,
                'invisible' => false,
            ],
            [
                'server_id' => 'streamer2',
                'name' => 'Goonstation Nightshade 2',
                'short_name' => 'Nightshade 2',
                'address' => 'tomato2.goonhub.com',
                'port' => 27112,
                'active' => true,
                'invisible' => false,
            ],
            [
                'server_id' => 'streamer3',
                'name' => 'Goonstation Nightshade 3',
                'short_name' => 'Nightshade 3',
                'address' => 'tomato3.goonhub.com',
                'port' => 27113,
                'active' => true,
                'invisible' => false,
            ],
            [
                'server_id' => 'streamer4',
                'name' => 'Goonstation Nightshade 4',
                'short_name' => 'Nightshade 4',
                'address' => 'tomato4.goonhub.com',
                'port' => 27114,
                'active' => true,
                'invisible' => false,
            ],
            [
                'server_id' => 'streamer5',
                'name' => 'Goonstation Nightshade 5',
                'short_name' => 'Nightshade 5',
                'address' => 'tomato5.goonhub.com',
                'port' => 27115,
                'active' => true,
                'invisible' => false,
            ],
        ]);

        Map::insert([
            ['map_id' => 'ATLAS', 'name' => 'Atlas', 'active' => true, 'is_layer' => false, 'tile_width' => 300, 'tile_height' => 300, 'screenshot_tiles' => 30],
            ['map_id' => 'COGMAP', 'name' => 'Cogmap', 'active' => true, 'is_layer' => false, 'tile_width' => 300, 'tile_height' => 300, 'screenshot_tiles' => 30],
            ['map_id' => 'COGMAP2', 'name' => 'Cogmap 2', 'active' => true, 'is_layer' => false, 'tile_width' => 300, 'tile_height' => 300, 'screenshot_tiles' => 30],
            ['map_id' => 'CLARION', 'name' => 'Clarion', 'active' => true, 'is_layer' => false, 'tile_width' => 300, 'tile_height' => 300, 'screenshot_tiles' => 30],
            ['map_id' => 'DEBRIS', 'name' => 'Debris', 'active' => true, 'is_layer' => true, 'tile_width' => 300, 'tile_height' => 300, 'screenshot_tiles' => 30],
            ['map_id' => 'DESTINY', 'name' => 'Destiny', 'active' => true, 'is_layer' => false, 'tile_width' => 300, 'tile_height' => 300, 'screenshot_tiles' => 30],
            ['map_id' => 'DONUT2', 'name' => 'Donut 2', 'active' => true, 'is_layer' => false, 'tile_width' => 300, 'tile_height' => 300, 'screenshot_tiles' => 30],
            ['map_id' => 'DONUT3', 'name' => 'Donut 3', 'active' => true, 'is_layer' => false, 'tile_width' => 300, 'tile_height' => 300, 'screenshot_tiles' => 30],
            ['map_id' => 'KONDARU', 'name' => 'Kondaru', 'active' => true, 'is_layer' => false, 'tile_width' => 300, 'tile_height' => 300, 'screenshot_tiles' => 30],
            ['map_id' => 'NADIR', 'name' => 'Nadir', 'active' => true, 'is_layer' => false, 'tile_width' => 300, 'tile_height' => 300, 'screenshot_tiles' => 30],
            ['map_id' => 'OSHAN', 'name' => 'Oshan', 'active' => true, 'is_layer' => false, 'tile_width' => 300, 'tile_height' => 300, 'screenshot_tiles' => 30],
            ['map_id' => 'POD_WARS', 'name' => 'Pod Wars', 'active' => true, 'is_layer' => false, 'tile_width' => 500, 'tile_height' => 500, 'screenshot_tiles' => 50],
        ]);
    }
}
