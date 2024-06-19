<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestController extends Controller
{
    /**
     * Test
     */
    public function index(Request $request)
    {
        return response()->json([
            'message' => 'This is a test',
            'data' => [
                'ip' => $request->ip(),
                'agent' => $request->userAgent(),
            ]
        ]);
    }
}
