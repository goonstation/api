<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    /**
     * @return mixed
     */
    public function toResponse($request)
    {
        $prev = $request->input('prev', false);
        $home = $prev ? redirect($prev) : redirect(config('fortify.home'));

        return $request->wantsJson()
            ? response()->json(['two_factor' => false])
            : $home;
    }
}
