<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('discord')->redirect();
    }

    public function callback()
    {
        $user = null;
        try {
            $discordUser = Socialite::driver('discord')->user();
            $user = User::where('discord_id', $discordUser->id)->first();
        } catch (\Exception $e) {
            // pass
        }

        if ($user) {
            Auth::login($user);

            return redirect(RouteServiceProvider::HOME);
        } else {
            // No user exists for this discord account, tell them their login failed
            return redirect('/login')
                ->with('error', 'Unable to login with Discord, please try again.');
        }
    }
}
