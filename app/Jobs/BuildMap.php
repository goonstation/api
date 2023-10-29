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
use Illuminate\Support\Str;
use ZipArchive;
use Illuminate\Support\Facades\File;

class BuildMap implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    // Just hardcoded dimensions based on how the ingame map-world proc is set up
    const MAP_SIZE = 9600;

    const SCREENSHOT_SIZE = 960;

    public static $workPath = 'app/map-processing';

    public static $publicMapsPath = 'app/public/maps';

    private $map = '';
    private $zipPath = null;
    private $workDir = null;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $map, string $zipPath)
    {
        $this->map = $map;
        $this->zipPath = $zipPath;
    }

    public static function getExpectedImageCount()
    {
        $imagesPerRow = self::MAP_SIZE / self::SCREENSHOT_SIZE;

        return pow($imagesPerRow, 2);
    }

    private function cleanup()
    {
        exec('rm -r "'.storage_path($this->workDir).'"');
        unlink($this->zipPath);
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

        // Generate our working directories
        $this->workDir = self::$workPath . '/' . Str::random(10);
        $workDirInput = $this->workDir.'/input';
        $workDirOutput = $this->workDir.'/output';
        mkdir(storage_path($this->workDir));
        mkdir(storage_path($workDirInput));
        mkdir(storage_path($workDirOutput));

        $zip = new ZipArchive();
        $zip->open($this->zipPath);
        $zip->extractTo(storage_path($workDirInput));
        $zip->close();

        // Make sure public output directory exists
        $outputPublic = self::$publicMapsPath."/$mapUri";
        if (! is_dir(storage_path($outputPublic))) {
            mkdir(storage_path($outputPublic));
        }

        $inputImages = File::allFiles(storage_path($workDirInput));
        $imagesPerRow = self::MAP_SIZE / self::SCREENSHOT_SIZE;
        if (count($inputImages) < $this->getExpectedImageCount()) {
            // $this->error('Too few images! Expected '.$this->getExpectedImageCount().' but got '.count($inputImages));
            return;
        }

        $canvas = Image::canvas(self::MAP_SIZE, self::MAP_SIZE);
        $imageIndex = 0;
        for ($y = 0; $y < $imagesPerRow; $y++) { //this is fine as long as maps remain squares
            for ($x = 0; $x < $imagesPerRow; $x++) {
                // $imagePath = storage_path('app/'.$inputImages[$imageIndex]);
                $imagePath = $inputImages[$imageIndex]->getRealPath();
                $image = Image::make($imagePath);

                $gdImage = $image->getCore();
                $colorToRemove = imagecolorallocate($gdImage, 255, 0, 228); // pink, #ff00e4
                imagecolortransparent($gdImage, $colorToRemove);
                imagepng($gdImage, storage_path($workDirOutput."/$x,$y.png"));

                $canvas->insert($gdImage, 'top-left', $x * self::SCREENSHOT_SIZE, $y * self::SCREENSHOT_SIZE);
                $imageIndex++;
            }
        }

        $canvas
            ->resize(200, 200)
            ->save(storage_path($workDirOutput.'/thumb.png'), 100);

        $files = scandir(storage_path($workDirOutput));
        $oldFolder = storage_path($workDirOutput).'/';
        $newFolder = storage_path($outputPublic).'/';
        foreach ($files as $fname) {
            if ($fname != '.' && $fname != '..') {
                rename($oldFolder.$fname, $newFolder.$fname);
            }
        }

        $map->last_built_at = Carbon::now();
        $map->save();
        $this->cleanup();
    }

    /**
     * Handle a job failure.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(\Throwable $exception)
    {
        $this->cleanup();
    }
}
