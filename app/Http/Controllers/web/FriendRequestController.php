<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Events\NewFriendRequest;
use App\Notifications\FriendRequestNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FriendRequestController extends Controller
{
    public function sendFriendRequest($userId)
{
    $sender = Auth::user(); // El usuario autenticado (quien envía la solicitud)
    $receiver = User::findOrFail($userId);  // El usuario que recibirá la solicitud

    // Llamar al procedimiento almacenado para manejar la solicitud de amistad
    $result = DB::select('CALL SendFriendRequest(?, ?)', [$sender->id, $receiver->id]);

    // Procesar el mensaje de respuesta del procedimiento almacenado
    $message = $result[0]->message;

    if ($message === 'Ya has enviado una solicitud de amistad a este usuario') {
        return response()->json(['message' => $message], 400);
    }

    // Si la solicitud fue creada, emitimos el evento y enviamos la notificación
    event(new NewFriendRequest($sender, $receiver));
    $receiver->notify(new FriendRequestNotification($sender));

    return response()->json(['message' => $message]);
}
}
