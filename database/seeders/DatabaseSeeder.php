<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

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
