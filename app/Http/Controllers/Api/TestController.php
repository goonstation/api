<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Events\EventLog;
use Illuminate\Http\Request;
use Meilisearch\Endpoints\Indexes;

class TestController extends Controller
{
    /**
     * Test
     */
    public function index(Request $request)
    {
        return response()->json([
            'Test' => 'This is a test',
        ]);
    }
}
