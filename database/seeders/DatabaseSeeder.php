<?php

namespace Database\Seeders;

use Kdabrow\SeederOnce\SeederOnce;

class DatabaseSeeder extends SeederOnce
{
    public bool $seedOnce = false;

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $seeders = scandir(dirname(__FILE__));
        foreach ($seeders as $file) {
            if ($file !== 'DatabaseSeeder.php' && $file[0] !== '.') {
                $this->call('Database\Seeders\\'.explode('.', $file)[0]);
            }
        }
    }
}
