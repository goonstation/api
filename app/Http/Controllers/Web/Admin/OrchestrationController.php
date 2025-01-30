<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Traits\ManagesOrchestration;
use Illuminate\Http\Request;

class OrchestrationController extends Controller
{
    use ManagesOrchestration;

    public function status(Request $request)
    {
        try {
            return $this->getServerStatus($request);
        } catch (\Throwable $e) {
            return abort(500, $e->getMessage() ?: 'Something went wrong');
        }
    }

    public function restart(Request $request)
    {
        try {
            $this->restartServer($request);

            return ['message' => 'Success'];
        } catch (\Throwable $e) {
            return abort(500, $e->getMessage() ?: 'Something went wrong');
        }
    }
}
