<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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
            'text' => 'required'
        ]);

        $fileName = Str::random(10);
        $filePathPrefix = storage_path('app/public/dectalk');

        $dectalkFilePath = $filePathPrefix . "/$fileName.wav";
        $process = proc_open(
            '/usr/local/bin/dectalk ' .
                '-pre "[:phoneme on]" ' .
                '-fo "' . $dectalkFilePath . '" ',
            [0 => ['pipe', 'r']],
            $pipes
        );

        fwrite($pipes[0], $data['text']);
        fclose($pipes[0]);
        $returnValue = proc_close($process);
        if ($returnValue !== 0) {
            return response()->json(['message' => 'Failed to run dectalk'], 400);
        }

        $mp3FilePath = $filePathPrefix . "/$fileName.mp3";
        exec("lame -V2 \"$dectalkFilePath\" \"$mp3FilePath\"");
        exec("rm \"$dectalkFilePath\"");

        return ['data' => [
            /**
             * A URL pointing to an MP3 file of the recorded text
             */
            'audio' => asset(Storage::url('dectalk/' . $fileName . '.mp3'))
        ]];
    }
}
