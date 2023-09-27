<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
{
    /**
     * @param    $request
     * @return mixed
     */
    public function toResponse($request)
    {
        return $request->wantsJson()
            ? response()->json($request->user())
            : '';
    }
}
