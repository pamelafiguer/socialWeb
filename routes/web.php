<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\redsocialController;

Route::controller(redsocialController::class) ->group(function() {
    Route::get('/', 'Login')->name('Login');
    Route::post('/', 'LoginForm')->name('LoginForm');

    Route::get('/Register', 'Register')->name('Register');
    Route::post('/Register', 'RegisterForm')->name('RegisterForm');
    
    Route::get('/feed', 'feed')->name('feed');
    

    Route::get('/Usuario', 'Usuario') ->name('Usuario');
    Route::post('/Usuario', 'Usuario')->name('Usuario');

    Route::get('/recuperarPassword', 'showRecoverForm')->name('recuperarPassword');
    Route::post('/recuperarPassword', 'recover')->name('procesar_recuperacion');

    Route::get('/cambiarPassword', 'showChangePasswordForm')->name('cambiar_password');
    Route::post('/cambiarPassword', 'changePassword')->name('actualizar_password');

    Route::get('/bienvenido', 'bienvenido')-> name('ingreso');

    Route::post('/logout', 'logout')-> name('logout');
    
    

});
