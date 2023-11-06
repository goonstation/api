<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\BuildMap;
use App\Models\Map;
use App\Traits\IndexableQuery;
use App\Traits\ManagesFileUploads;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\AbstractHandler;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use ZipArchive;

class MapsController extends Controller
{
    use IndexableQuery, ManagesFileUploads;

    public function index(Request $request)
    {
        $maps = $this->indexQuery(
            Map::with(['gameAdmin']),
            sortBy: 'name',
            desc: false,
            perPage: 30
        );

        if ($this->wantsInertia($request)) {
            return Inertia::render('Admin/Maps/Index', [
                'maps' => $maps,
            ]);
        } else {
            return $maps;
        }
    }

    public function showUpload()
    {
        return Inertia::render('Admin/Maps/Upload', [
            'maps' => Map::orderBy('name', 'asc')->get(),
        ]);
    }

    public function upload(Request $request)
    {
        $data = $request->validate([
            'map' => 'required|exists:maps,map_id',
            'fileName' => 'required|string',
            'filePath' => 'required|string',
        ]);

        $gameAdminId = Auth::user()->game_admin_id;

        $finalPath = storage_path('app/'.$data['filePath'].$data['fileName']);
        $file = new File($finalPath);

        $mapZipPath = BuildMap::moveUploadedFile($file);
        $job = new BuildMap($data['map'], $mapZipPath, $gameAdminId);

        // image count check
        $zip = new ZipArchive();
        $zip->open($mapZipPath);
        $expectedImageCount = $job->getExpectedImageCount();
        $imageCount = $zip->count();
        if ($imageCount !== $expectedImageCount) {
            $job->cleanup();
            return Redirect::back()->withErrors(['error' => "Expected an archive containing $expectedImageCount files, saw $imageCount."]);
        }
        $zip->close();

        $job->dispatch($data['map'], $mapZipPath, $gameAdminId);

        return Redirect::back();
    }

    /**
     * Handles the file upload
     *
     * @param  FileReceiver  $receiver
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws UploadMissingFileException
     */
    public function uploadFile(FileReceiver $receiver)
    {
        // check if the upload is success, throw exception or return response you need
        if ($receiver->isUploaded() === false) {
            throw new UploadMissingFileException();
        }
        // receive the file
        $save = $receiver->receive();

        // check if the upload has finished (in chunk mode it will send smaller files)
        if ($save->isFinished()) {
            // save the file and return any response you need
            $fileDetails = $this->saveFile($save->getFile());
            return response()->json([
                'path' => $fileDetails['path'],
                'name' => $fileDetails['name'],
                'mime_type' => $fileDetails['mime_type'],
            ]);
        }

        // we are in chunk mode, lets send the current progress
        /** @var AbstractHandler $handler */
        $handler = $save->handler();

        return response()->json([
            'done' => $handler->getPercentageDone(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Maps/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'map_id' => 'required|string|uppercase',
            'name' => 'required',
            'active' => 'required|boolean',
            'is_layer' => 'required|boolean',
            'tile_width' => 'required|integer',
            'tile_height' => 'required|integer',
        ]);

        $map = new Map();
        $map->map_id = $data['map_id'];
        $map->name = $data['name'];
        $map->active = $data['active'];
        $map->is_layer = $data['is_layer'];
        $map->tile_width = $data['tile_width'];
        $map->tile_height = $data['tile_height'];
        $map->screenshot_tiles = $data['tile_width'] / 10;
        $map->save();

        return to_route('admin.maps.index');
    }

    public function edit(Map $map)
    {
        return Inertia::render('Admin/Maps/Edit', [
            'map' => $map,
        ]);
    }

    public function update(Request $request, Map $map)
    {
        $data = $request->validate([
            'map_id' => 'required|string|uppercase',
            'name' => 'required',
            'active' => 'required|boolean',
            'is_layer' => 'required|boolean',
            'tile_width' => 'required|integer',
            'tile_height' => 'required|integer',
        ]);

        $map->map_id = $data['map_id'];
        $map->name = $data['name'];
        $map->active = $data['active'];
        $map->is_layer = $data['is_layer'];
        $map->tile_width = $data['tile_width'];
        $map->tile_height = $data['tile_height'];
        $map->screenshot_tiles = $data['tile_width'] / 10;
        $map->save();

        return to_route('admin.maps.index');
    }
}
