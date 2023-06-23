<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\BuildMap;
use App\Models\Map;
use Illuminate\Http\Request;
use ZipArchive;

class MapsController extends Controller
{
    /**
     * Build
     *
     * Dispatches a job to process map screenshots and upload them to the web map viewer
     */
    public function build(Request $request)
    {
        $data = $this->validate($request, [
            'map' => 'required',
            'images' => 'required|file|mimes:zip',
        ]);

        $map = Map::where('uri', '=', $data['map'])->where('active', '=', true)->first();
        if (! $map) {
            return response()->json(['error' => 'Unable to locate configuration for that map.'], 400);
        }

        $zip = new ZipArchive();
        $file = $request->file('images');
        $zip->open($file->path());

        $expectedImageCount = BuildMap::getExpectedImageCount();
        $imageCount = $zip->count();
        if ($imageCount !== $expectedImageCount) {
            return response()->json(['error' => "Expected an archive containing $expectedImageCount files, saw $imageCount."], 400);
        }

        $inputPath = BuildMap::$inputPath;
        $storageInputPath = storage_path($inputPath);

        // Create or empty input directory
        if (! is_dir($storageInputPath)) {
            mkdir($storageInputPath);
        } else {
            array_map('unlink', array_filter((array) glob("$storageInputPath/*")));
        }

        $zip->extractTo($storageInputPath);
        $zip->close();

        BuildMap::dispatch($data['map']);

        return ['message' => 'Success'];
    }
}
