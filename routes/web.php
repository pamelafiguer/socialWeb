<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\web\redsocialController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Middleware\CustomAuthMiddleware;

Route::controller(redsocialController::class)->group(function () {
    Route::get('/', 'Login')->name('login');
    Route::post('/', 'LoginForm')->name('LoginForm');
    Route::get('/Register', 'Register')->name('Register');
    Route::post('/Register', 'RegisterForm')->name('RegisterForm');
});

Route::middleware([CustomAuthMiddleware::class])->group(function () {
    Route::get('/feed', [redsocialController::class, 'feed'])->name('feed');
    Route::post('/feed', [redsocialController::class, 'Nuevofeed'])->name('Nuevofeed');
    Route::post('/comentar/{id}', [redsocialController::class, 'comentar'])->name('comentar');
    Route::post('/reaccionar/{id}', [redsocialController::class, 'reaccionar'])->name('reaccionar');

    Route::get('/Usuario', [redsocialController::class, 'Usuario'])->name('Usuario');

    Route::get('/amigos', [redsocialController::class, 'listFriends'])->name('amigos');
    Route::get('/Solicitudes', [redsocialController::class, 'receivedRequests'])->name('Solicitudes');
    Route::get('/EnviarSolicitudes', [redsocialController::class, 'EnviarSolicitudes']);
    Route::post('/enviarSolicitud/{receiverId}', [redsocialController::class, 'enviarSolicitud'])->name('enviarSolicitud');
    Route::post('/aceptarSolicitud/{senderId}', [redsocialController::class, 'Solicitud'])->name('aceptarSolicitud');
    Route::post('/reaccionar/{id}', [redsocialController::class, 'reaccionar'])->name('reaccionar');


    Route::get('/buscar', [redsocialController::class, 'buscar'])->name('buscar');
    Route::get('/videos', [redsocialController::class, 'videos'])->name('videos');
    Route::post('/logout', [redsocialController::class, 'logout'])->name('logout');

    Route::prefix('usuario')->group(function () {
        Route::get('/publicaciones', [redsocialController::class, 'Usuario'])->name('usuario.publicaciones');
        Route::get('/informacion', [redsocialController::class, 'Usuario'])->name('usuario.informacion');
        Route::get('/amigos', [redsocialController::class, 'Usuario'])->name('usuario.amigos');
        Route::get('/fotos', [redsocialController::class, 'Usuario'])->name('usuario.fotos');


        Route::post('/actualizar-foto-perfil', [redsocialController::class, 'actualizarFotoPerfil'])->name('usuario.actualizar-foto-perfil');
        Route::post('/actualizar-foto-portada', [redsocialController::class, 'actualizarFotoPortada'])->name('usuario.actualizar-foto-portada');
        Route::post('/editar-perfil', [redsocialController::class, 'editarPerfil'])->name('usuario.editar-perfil');
    });
});

Route::get('auth/{provider}', [SocialAuthController::class, 'redirectToProvider'])->name('auth.social');
Route::get('auth/{provider}/callback', [SocialAuthController::class, 'handleProviderCallback'])->name('auth.social.callback');
Route::get('logout', [SocialAuthController::class, 'logout'])->name('social.logout');
