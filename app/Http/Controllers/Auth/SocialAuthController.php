<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;


use Laravel\Socialite\Facades\Socialite;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class SocialAuthController extends Controller
{
    public function redirectToProvider($provider) {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        
        $socialUser = Socialite::driver($provider)->user();

        $password = str::random(10);

        DB::statement('call Usuario_Login(?,?)', [$socialUser->getEmail(), $password]);
        
        Session::put('user', [
            'name' => $socialUser->getName(),
            'email' => $socialUser->getEmail(),
            'avatar' => $socialUser->getAvatar(),
        ]);

        return redirect('/feed')->with('success', 'Ingreso exitoso con ' . ucfirst($provider));
    }

    public function logout()
{
    Session::forget('user');
    return redirect('/')->with('success', 'Has cerrado sesión.');
}
}
