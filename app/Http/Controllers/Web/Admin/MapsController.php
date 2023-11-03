<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\BuildMap;
use App\Models\Map;
use App\Traits\IndexableQuery;
use App\Traits\ManagesFileUploads;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Pion\Laravel\ChunkUpload\Handler\AbstractHandler;
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

        $finalPath = storage_path("app/" . $data['filePath'] . $data['fileName']);
        $file = new File($finalPath);
        $mapZipPath = $file->move(storage_path(BuildMap::$workPath), $data['fileName']);
        BuildMap::dispatch($data['map'], $mapZipPath);

        return Redirect::back();
    }

    /**
     * Handles the file upload
     *
     * @param FileReceiver $receiver
     * @return \Illuminate\Http\JsonResponse
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

            $zip = new ZipArchive();
            $zip->open($fileDetails['storage_path']);

            $expectedImageCount = BuildMap::getExpectedImageCount();
            $imageCount = $zip->count();
            if ($imageCount !== $expectedImageCount) {
                Storage::delete($fileDetails['path'] . $fileDetails['name']);
                return response()->json(['error' => "Expected an archive containing $expectedImageCount files, saw $imageCount."], 400);
            }
            $zip->close();

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
            'done' => $handler->getPercentageDone()
        ]);
    }
}
