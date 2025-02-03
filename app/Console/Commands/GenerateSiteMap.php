<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;

class GenerateSiteMap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gh:generate-sitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a sitemap.xml file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Sitemap::create()
            ->add([
                '/',
                '/changelog',
                '/players',
                '/rounds',
                '/events',
                '/events/deaths',
                '/events/tickets',
                '/events/fines',
                '/events/antags',
                '/events/errors',
                '/maps',
                '/medals',
            ])
            ->writeToFile(public_path('sitemap.xml'));

        return Command::SUCCESS;
    }
}
