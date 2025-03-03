<?php

namespace Database\Seeders;

use App\Models\Map;
use Kdabrow\SeederOnce\SeederOnce;

class MapSeeder extends SeederOnce
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Map::insert([
            ['map_id' => 'ATLAS', 'name' => 'Atlas', 'active' => true, 'is_layer' => false, 'tile_width' => 300, 'tile_height' => 300],
            ['map_id' => 'COGMAP', 'name' => 'Cogmap', 'active' => true, 'is_layer' => false, 'tile_width' => 300, 'tile_height' => 300],
            ['map_id' => 'COGMAP2', 'name' => 'Cogmap 2', 'active' => true, 'is_layer' => false, 'tile_width' => 300, 'tile_height' => 300],
            ['map_id' => 'CLARION', 'name' => 'Clarion', 'active' => true, 'is_layer' => false, 'tile_width' => 300, 'tile_height' => 300],
            ['map_id' => 'DEBRIS', 'name' => 'Debris', 'active' => true, 'is_layer' => true, 'tile_width' => 300, 'tile_height' => 300],
            ['map_id' => 'DESTINY', 'name' => 'Destiny', 'active' => true, 'is_layer' => false, 'tile_width' => 300, 'tile_height' => 300],
            ['map_id' => 'DONUT2', 'name' => 'Donut 2', 'active' => true, 'is_layer' => false, 'tile_width' => 300, 'tile_height' => 300],
            ['map_id' => 'DONUT3', 'name' => 'Donut 3', 'active' => true, 'is_layer' => false, 'tile_width' => 300, 'tile_height' => 300],
            ['map_id' => 'KONDARU', 'name' => 'Kondaru', 'active' => true, 'is_layer' => false, 'tile_width' => 300, 'tile_height' => 300],
            ['map_id' => 'NADIR', 'name' => 'Nadir', 'active' => true, 'is_layer' => false, 'tile_width' => 300, 'tile_height' => 300],
            ['map_id' => 'OSHAN', 'name' => 'Oshan', 'active' => true, 'is_layer' => false, 'tile_width' => 300, 'tile_height' => 300],
            ['map_id' => 'POD_WARS', 'name' => 'Pod Wars', 'active' => true, 'is_layer' => false, 'tile_width' => 500, 'tile_height' => 500],
        ]);
    }
}
