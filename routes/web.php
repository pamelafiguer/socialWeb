<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\redsocialController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\SocialAuthController;

Route::controller(redsocialController::class)->group(function() {
    Route::get('/', 'Login')->name('login');
    Route::post('/', 'LoginForm')->name('LoginForm');

    Route::get('/Register', 'Register')->name('Register');
    Route::post('/Register', 'RegisterForm')->name('RegisterForm');

    Route::get('/recuperarPassword', 'showRecoverForm')->name('recuperarPassword');
    Route::post('/recuperarPassword', 'recover')->name('procesar_recuperacion');

    Route::get('/cambiarPassword', 'showChangePasswordForm')->name('cambiar_password');
    Route::post('/cambiarPassword', 'changePassword')->name('actualizar_password');
});

// Rutas protegidas por el middleware de autenticación
Route::middleware(['auth'])->group(function () {
    Route::get('/feed', [redsocialController::class, 'feed'])->name('feed');
    Route::post('/feed', [redsocialController::class, 'Nuevofeed'])->name('Nuevofeed');

    Route::get('/Usuario', [redsocialController::class, 'Usuario'])->name('Usuario');
    Route::post('/Usuario', [redsocialController::class, 'UsuarioForm'])->name('UsuarioForm');

    Route::get('/amigos', [redsocialController::class, 'Amigos'])->name('Amigos');
    Route::get('/videos', [redsocialController::class, 'videos'])->name('videos');

    Route::post('/logout', [redsocialController::class, 'logout'])->name('logout');
    
    // Ruta para obtener notificaciones no leídas
    Route::get('/notifications', function () {
        return Auth::user()->unreadNotifications;
    })->name('notifications');
});

// Rutas para autenticación social
Route::get('auth/{provider}', [SocialAuthController::class, 'redirectToProvider'])->name('auth.social');
Route::get('auth/{provider}/callback', [SocialAuthController::class, 'handleProviderCallback'])->name('auth.social.callback');
Route::get('logout', [SocialAuthController::class, 'logout'])->name('social.logout'); 
