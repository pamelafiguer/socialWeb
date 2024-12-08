<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class SocialAuthController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        
        $socialUser = Socialite::driver($provider)->user();

        $fullName = explode(' ', $socialUser->getName());
        $firstName = array_shift($fullName);
        $lastName = implode(' ', $fullName);

        $user = User::where('email', $socialUser->getEmail())->first();

        if (!$user) {
            
            $user = User::create([
                'nombre' => $firstName,
                'apellidos' => $lastName,
                'fecha_nacimiento' => now(), 
                'Genero' => 'Masculino', 
                'email' => $socialUser->getEmail(),
                'passwordd' => Str::random(10),
                'foto_perfil' => $socialUser->getAvatar(),
            ]);
        }

        
        Auth::login($user);

        
        Session::put('user', [
            'name' => $socialUser->getName(),
            'email' => $socialUser->getEmail(),
            'avatar' => $socialUser->getAvatar(),
        ]);

        
        return redirect('/feed')->with('success', 'Ingreso exitoso con ' . ucfirst($provider));
    }

    public function logout()
    {
        Auth::logout(); 
        Session::forget('user');
        return redirect('/')->with('success', 'Has cerrado sesión.');
    }
}
