<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Events\EventLog;
use Illuminate\Http\Request;

class TestController extends Controller
{
    /**
     * Test
     */
    public function index(Request $request)
    {
        $logs = EventLog::search('clown')->paginate();
        return $logs;
        // return response()->json([
        //     'Test' => 'This is a test',
        // ]);
    }
}
