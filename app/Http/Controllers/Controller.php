<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Determine whether a request should respond with an Inertia object
     *
     * @return bool
     */
    public function wantsInertia(Request $request = null)
    {
        if (! $request) {
            $request = request();
        }

        return $request->hasHeader('X-Inertia') || ! $request->hasHeader('X-Requested-With');
    }
}
