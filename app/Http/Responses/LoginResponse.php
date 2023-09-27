<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    /**
     * @param    $request
     * @return mixed
     */
    public function toResponse($request)
    {
        $prev = $request->input('prev', false);
        $home = $prev ? redirect($prev) : config('fortify.dashboard');

        return $request->wantsJson()
            ? response()->json(['two_factor' => false])
            : $home;
    }
}
