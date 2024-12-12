<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DectalkPhrase;
use Illuminate\Http\Request;
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

        $dectalkFilePath = $filePathPrefix."/$fileName.wav";
        $process = proc_open(
            '/usr/local/bin/dectalk '.
                '-pre "[:phoneme on]" '.
                '-fo "'.$dectalkFilePath.'" ',
            [0 => ['pipe', 'r']],
            $pipes
        );

        fwrite($pipes[0], $data['text']);
        fclose($pipes[0]);
        $returnValue = proc_close($process);
        if ($returnValue !== 0) {
            return response()->json(['message' => 'Failed to run dectalk'], 400);
        }

        $mp3FilePath = $filePathPrefix."/$fileName.mp3";
        exec("lame -V2 \"$dectalkFilePath\" \"$mp3FilePath\" 2>&1 >/dev/null");
        exec("rm \"$dectalkFilePath\"");

        $dectalkPhrase = new DectalkPhrase;
        $dectalkPhrase->phrase = $data['text'];
        $dectalkPhrase->round_id = $data['round_id'];
        $dectalkPhrase->save();

        return ['data' => [
            /**
             * A URL pointing to an MP3 file of the recorded text
             */
            'audio' => asset(Storage::url('dectalk/'.$fileName.'.mp3')),
        ]];
    }
}
