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
            'message' => app(\Swoole\Http\Server::class)->stats(1),
        ]);
    }
}
