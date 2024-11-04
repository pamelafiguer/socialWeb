<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\redsocialController;

Route::controller(redsocialController::class) ->group(function() {
    Route::get('/', 'Login')->name('Login');
    Route::post('/', 'Login')->name('Login');

    Route::get('/Register', 'Register')->name('Register');
    Route::post('/Register', 'Register')->name('Register');
    
    Route::get('/feed', 'feed')->name('feed');
    

    Route::get('/recuperarPassword', 'showRecoverForm')->name('recuperarPassword');
    Route::post('/recuperarPassword', 'recover')->name('procesar_recuperacion');

    Route::get('/cambiarPassword', 'showChangePasswordForm')->name('cambiar_password');
    Route::post('/cambiarPassword', 'changePassword')->name('actualizar_password');

    Route::get('/bienvenido', 'bienvenido')-> name('ingreso');

    Route::post('/logout', 'logout')-> name('logout');
    
    Route::get('/verificar', 'showMesaggeVerificar')-> name('verificar');

});
