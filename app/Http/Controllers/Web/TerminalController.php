<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Jobs\PrintGameMysteryFile;
use App\Models\NumbersStationPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class TerminalController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Terminal/Index');
    }

    public function login(Request $request)
    {
        $password = $request->input('password');

        $numbersPassword = NumbersStationPassword::first();

        if ($password === $numbersPassword->password) {
            return response()->json(['message' => 'Logged in']);
        }

        return response()->json(['message' => 'Wrong passphrase'], 401);
    }

    public function sudo(Request $request)
    {
        $password = $request->input('password');

        if ($password === 'woodo') {
            return response()->json(['message' => 'Logged in']);
        }

        return response()->json(['message' => 'Wrong passphrase'], 401);
    }

    public function print(Request $request)
    {
        $fileName = $request->input('fileName');

        if (!str_ends_with($fileName, 'file#002-3')) {
            return response()->json(['message' => 'cannot print file'], 400);
        }

        if (Cache::has('terminal-print-queue')) {
            return response()->json(['message' => 'file already sent to queue'], 400);
        }

        Cache::put('terminal-print-queue', true, now()->addMinutes(60));

        PrintGameMysteryFile::dispatch('011010110110010101111001', 'mystery01.txt');

        return response()->json(['message' => 'Printing ' . $fileName]);
    }
}
