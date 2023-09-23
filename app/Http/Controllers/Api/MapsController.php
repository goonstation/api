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
     * Generate
     *
     * Dispatches a job to process map screenshots and upload them to the web map viewer
     *
     * The uploaded zip file should contain images taken with the ingame Map-World verb.
     * For a regular 300x300 map, this should be 100 images.
     * The names of the individual image files should remain as what the Map-World verb names them.
     * This route is intended for out-of-game usage only.
     *
     * Example:
     *
     * ```sh
     * curl --request POST \
     *   --url {base_url}/api/maps/generate \
     *   --header 'Authorization: Bearer {bearer_token}' \
     *   --header 'Content-Type: multipart/form-data' \
     *   --form map=oshan \
     *   --form 'images=@{full_path_to_file}\Oshan Laboratory, Abzu 2023-06-15 172448.zip'
     * ```
     */
    public function generate(Request $request)
    {
        $data = $this->validate($request, [
            'map' => 'required',
            /** A zip file containing map screenshot images */
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
