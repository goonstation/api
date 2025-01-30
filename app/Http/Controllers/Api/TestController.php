<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Github\ResultPager;
use GrahamCampbell\GitHub\Facades\GitHub;
use Illuminate\Http\Request;

class TestController extends Controller
{
    /**
     * Test
     */
    public function index(Request $request)
    {
        // 21997
        // S-Testmerged

        // GitHub::issue()->labels()->all('goonstation', 'goonstation', 21997)
        // GitHub::issue()->labels()->add('goonstation', 'goonstation', 21997, 'S-Testmerged')
        // GitHub::issue()->labels()->remove('goonstation', 'goonstation', 21997, 'S-Testmerged'),

        /** @var \Github\Client */
        $conn = GitHub::connection();
        $test = $conn->issue()->labels()->all(config('goonhub.github_organization'), config('goonhub.github_repo'), 21997);

        // $pagination = new ResultPager($conn);
        // $test = $pagination->fetchAll($conn->pullRequests(), 'all', ['goonstation', 'goonstation', ['state' => 'open']]);

        return response()->json([
            'message' => $test,
        ]);
    }
}
