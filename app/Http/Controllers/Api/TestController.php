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
        return EventLog::search(
            'clown',
            function (Indexes $searchEngine, string $query, array $options) {
                $options['attributesToSearchOn'] = ['source'];
                return $searchEngine->search($query, $options);
            }
        )
            ->orderBy('created_at', 'desc')
            ->paginate();
        // return response()->json([
        //     'Test' => 'This is a test',
        // ]);
    }
}
