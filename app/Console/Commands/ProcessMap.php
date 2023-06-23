<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class ProcessMap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process-map {map}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process a map';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $map = $this->argument('map');
        $map = preg_replace('/[^A-Za-z0-9]/', '', $map);
        $outputPublic = "app/public/maps/$map";
        if (! is_dir(storage_path($outputPublic))) {
            $this->error('No public folder for that map exists.');

            return Command::FAILURE;
        }
        if (! is_dir(storage_path('app/map-processing/input'))) {
            $this->error('No input folder exists.');

            return Command::FAILURE;
        }

        // Just hardcoded dimensions based on how the ingame map-world proc is set up
        $mapSize = 9600;
        $screenshotSize = 960;
        $imagesPerRow = $mapSize / $screenshotSize;

        $inputImages = Storage::disk('local')->files('map-processing/input');
        $outputBuild = 'app/map-processing/output';

        if (count($inputImages) < pow($imagesPerRow, 2)) {
            $this->error('Too few images! Expected '.pow($imagesPerRow, 2).' but got '.count($inputImages));

            return Command::FAILURE;
        }

        if (! is_dir(storage_path($outputBuild))) {
            mkdir(storage_path($outputBuild));
        }

        $canvas = Image::canvas($mapSize, $mapSize);
        $imageIndex = 0;
        for ($y = 0; $y < $imagesPerRow; $y++) {
            for ($x = 0; $x < $imagesPerRow; $x++) {
                $imagePath = storage_path('app/'.$inputImages[$imageIndex]);
                $image = Image::make($imagePath);

                $gdImage = $image->getCore();
                $colorToRemove = imagecolorallocate($gdImage, 255, 0, 228); // pink, #ff00e4
                imagecolortransparent($gdImage, $colorToRemove);
                imagepng($gdImage, storage_path("$outputBuild/$x,$y.png"));

                $canvas->insert($gdImage, 'top-left', $x * $screenshotSize, $y * $screenshotSize);
                $imageIndex++;
            }
        }

        $canvas
            ->resize(200, 200)
            ->save(storage_path("$outputBuild/thumb.png"), 100);

        $files = scandir(storage_path($outputBuild));
        $oldFolder = storage_path($outputBuild).'/';
        $newFolder = storage_path($outputPublic).'/';
        foreach ($files as $fname) {
            if ($fname != '.' && $fname != '..') {
                rename($oldFolder.$fname, $newFolder.$fname);
            }
        }

        $this->info('Done!');

        return Command::SUCCESS;
    }
}
