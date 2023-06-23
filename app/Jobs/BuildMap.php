<?php

namespace App\Jobs;

use App\Models\Map;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class BuildMap implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    // Just hardcoded dimensions based on how the ingame map-world proc is set up
    const MAP_SIZE = 9600;

    const SCREENSHOT_SIZE = 960;

    public static $inputPath = 'app/map-processing/input';

    public static $outputPath = 'app/map-processing/output';

    public static $publicMapsPath = 'app/public/maps';

    private $map = '';

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $map)
    {
        $this->map = $map;
    }

    public static function getExpectedImageCount()
    {
        $imagesPerRow = self::MAP_SIZE / self::SCREENSHOT_SIZE;

        return pow($imagesPerRow, 2);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $mapUri = $this->map;
        $mapUri = preg_replace('/[^A-Za-z0-9]/', '', $mapUri);

        $map = Map::where('uri', '=', $mapUri)->where('active', '=', true)->first();
        if (! $map) {
            return;
        }

        $outputPublic = self::$publicMapsPath."/$mapUri";
        if (! is_dir(storage_path($outputPublic))) {
            mkdir(storage_path($outputPublic));
        }
        if (! is_dir(storage_path(self::$inputPath))) {
            mkdir(storage_path(self::$inputPath));
        }

        $inputImages = Storage::disk('local')->files('map-processing/input');
        $imagesPerRow = self::MAP_SIZE / self::SCREENSHOT_SIZE;
        if (count($inputImages) < $this->getExpectedImageCount()) {
            // $this->error('Too few images! Expected '.$this->getExpectedImageCount().' but got '.count($inputImages));
            return;
        }

        if (! is_dir(storage_path(self::$outputPath))) {
            mkdir(storage_path(self::$outputPath));
        }

        $canvas = Image::canvas(self::MAP_SIZE, self::MAP_SIZE);
        $imageIndex = 0;
        for ($y = 0; $y < $imagesPerRow; $y++) { //this is fine as long as maps remain squares
            for ($x = 0; $x < $imagesPerRow; $x++) {
                $imagePath = storage_path('app/'.$inputImages[$imageIndex]);
                $image = Image::make($imagePath);

                $gdImage = $image->getCore();
                $colorToRemove = imagecolorallocate($gdImage, 255, 0, 228); // pink, #ff00e4
                imagecolortransparent($gdImage, $colorToRemove);
                imagepng($gdImage, storage_path(self::$outputPath."/$x,$y.png"));

                $canvas->insert($gdImage, 'top-left', $x * self::SCREENSHOT_SIZE, $y * self::SCREENSHOT_SIZE);
                $imageIndex++;
            }
        }

        $canvas
            ->resize(200, 200)
            ->save(storage_path(self::$outputPath.'/thumb.png'), 100);

        $files = scandir(storage_path(self::$outputPath));
        $oldFolder = storage_path(self::$outputPath).'/';
        $newFolder = storage_path($outputPublic).'/';
        foreach ($files as $fname) {
            if ($fname != '.' && $fname != '..') {
                rename($oldFolder.$fname, $newFolder.$fname);
            }
        }

        $map->last_built_at = Carbon::now();
        $map->save();
    }
}
