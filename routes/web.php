<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\redsocialController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\SocialAuthController;


Route::controller(redsocialController::class) ->group(function() {
    Route::get('/', 'Login')->name('login');
    Route::post('/', 'LoginForm')->name('LoginForm');

    Route::get('/Register', 'Register')->name('Register');
    Route::post('/Register', 'RegisterForm')->name('RegisterForm');
    
    Route::get('/feed', 'feed')->name('feed');
    Route::post('/feed', 'Nuevofeed')->name('Nuevofeed');
    

    Route::get('/Usuario', 'Usuario') ->name('Usuario');
    Route::post('/Usuario', 'UsuarioForm')->name('UsuarioForm');

    Route::get('/amigos', 'Amigos') ->name('Amigos');
    Route::get('/videos', 'videos') ->name('videos');
    

    Route::get('/recuperarPassword', 'showRecoverForm')->name('recuperarPassword');
    Route::post('/recuperarPassword', 'recover')->name('procesar_recuperacion');

    Route::get('/cambiarPassword', 'showChangePasswordForm')->name('cambiar_password');
    Route::post('/cambiarPassword', 'changePassword')->name('actualizar_password');



    Route::post('/logout', 'logout')-> name('logout');

});
Route::get('/notifications', function () {
    return  Auth::user()->unreadNotifications;
})->middleware('auth')->name('notifications');

Route::get('auth/{provider}', [SocialAuthController::class, 'redirectToProvider'])->name('auth.social');
Route::get('auth/{provider}/callback', [SocialAuthController::class, 'handleProviderCallback'])->name('auth.social.callback');
Route::get('logout', [SocialAuthController::class, 'logout']);