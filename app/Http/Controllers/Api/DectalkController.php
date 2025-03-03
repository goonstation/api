<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DectalkPhrase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DectalkController extends Controller
{
    /**
     * Play
     *
     * Generate an audio file speaking the text provided.
     *
     * Returns a URL pointing to the MP3 file of the recorded text
     */
    public function play(Request $request)
    {
        $data = $request->validate([
            'text' => 'required',
            'round_id' => 'required|exists:game_rounds,id',
        ]);

        $fileName = Str::random(10);
        $filePathPrefix = storage_path('app/public/dectalk');

        if (File::missing($filePathPrefix)) {
            File::makeDirectory($filePathPrefix, recursive: true);
        }

        $dectalkFilePath = "$filePathPrefix/$fileName.wav";
        $result = Process::input($data['text'])->timeout(5)
            ->run('/usr/local/bin/dectalk -pre "[:phoneme on]" -fo "'.$dectalkFilePath.'"');

        if ($result->failed()) {
            return response()->json(['message' => 'Failed to run dectalk'], 500);
        }

        $mp3FilePath = "$filePathPrefix/$fileName.mp3";
        $result = Process::run(['/usr/bin/lame', '-V2', $dectalkFilePath, $mp3FilePath]);
        File::delete($dectalkFilePath);

        if ($result->failed()) {
            return response()->json(['message' => 'Failed to run dectalk'], 500);
        }

        $dectalkPhrase = new DectalkPhrase;
        $dectalkPhrase->phrase = $data['text'];
        $dectalkPhrase->round_id = $data['round_id'];
        $dectalkPhrase->save();

        return ['data' => [
            /**
             * A URL pointing to an MP3 file of the recorded text
             */
            'audio' => asset(Storage::url("dectalk/$fileName.mp3")),
        ]];
    }
}
