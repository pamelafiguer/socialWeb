<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Events\NewFriendRequest;
use App\Notifications\FriendRequestNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;



class redsocialController extends Controller
{


    public function Login()
    {
        return view('login');
    }

    public function buscar(Request $request)
    {
        $query = $request->input('query');

        // Llamar al procedimiento almacenado
        $results = DB::select("CALL BuscarClientes(?)", [$query]);
    
        // Devolver los resultados en formato JSON
        return response()->json($results);
    }

    public function Amigos()

    {
        $userId = Auth::id(); // Obtenemos el ID del usuario autenticado

        // Llamada al procedimiento almacenado
        $solicitudes = DB::select('CALL ObtenerSolicitudesDeAmistadPendientes(?)', [$userId]);

        // Pasamos las solicitudes a la vista
        return view('Amigos', compact('solicitudes'));
        
    }

    public function enviarSolicitud($idUsuario1, $idUsuario2)
    {
        try {
            DB::statement('CALL EnviarSolicitudAmistad(?, ?)', [$idUsuario1, $idUsuario2]);
            return response()->json(['message' => 'Solicitud de amistad enviada correctamente.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function aceptarSolicitud($idUsuario1, $idUsuario2)
    {
        try {
            DB::statement('CALL AceptarSolicitudAmistad(?, ?)', [$idUsuario1, $idUsuario2]);
            return response()->json(['message' => 'Solicitud de amistad aceptada correctamente.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function rechazarSolicitud($idUsuario1, $idUsuario2)
    {
        try {
            DB::statement('CALL RechazarSolicitudAmistad(?, ?)', [$idUsuario1, $idUsuario2]);
            return response()->json(['message' => 'Solicitud de amistad rechazada correctamente.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function videos()
    {
        return view('videos');
    }

    public function Nuevofeed(Request $request)

    {
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors('Debes iniciar sesión para publicar.');
        }

        $request->validate([
            'content' => 'required|string',
            'images.*' => 'nullable|image|max:2048'
        ]);

        $userId = Auth::id();
        $content = $request->content;
        $imagePaths = [];


        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('/public/posts');
                $imagePaths = str_replace('/storage', '', $path);
            }
        }

        try {
            DB::statement("CALL Crear_Publicacion(?, ?, ?, ?)", [
                $userId,
                $content,
                json_encode($imagePaths),
                now()->toDateString()
            ]);


            return redirect()->back()->with('success', 'Publicación creada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Error al crear la publicación: ' . $e->getMessage());
        }
    }



    public function sendFriendRequest($userId)
    {
        $sender = Auth::user();
        $receiver = User::findOrFail($userId);


        $result = DB::select('CALL SendFriendRequest(?, ?)', [$sender->id, $receiver->id]);


        $message = $result[0]->message;

        if ($message === 'Ya has enviado una solicitud de amistad a este usuario') {
            return response()->json(['message' => $message], 400);
        }


        event(new NewFriendRequest($sender, $receiver));
        $receiver->notify(new FriendRequestNotification($sender));

        return response()->json(['message' => $message]);
    }

    public function LoginForm(Request $request)
    {

        // Validación de los datos
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|int',
        ]);

        // Llamar al procedimiento almacenado para obtener los datos del usuario
        $usuario = DB::select("CALL Usuario_Login(?, ?)", [$request->email, $request->password]);



        if (count($usuario) > 0) {
            $usuario = $usuario[0];

            // Comparar la contraseña directamente
            $usuario = User::find($usuario->id_usuario);
            if ($usuario) {
                Auth::login($usuario);  // Haces login con el objeto completo
                session(['usuario_nombre' => Auth::user()->nombre]); // Obtienes el nombre del usuario autenticado
                return redirect('/feed')->with('usuario_nombre');
            } else {
                return back()->withErrors(['email' => 'Usuario no encontrado.']);
            }
        }
    }




    public function Register()
    {
        return view('Register');
    }
    public function RegisterForm(Request $request)
    {

        $validatedData = $request->validate([
            'Nombres' => 'required|string|max:50',
            'Apellidos' => 'required|string|max:100',
            'birthday_day' => 'required|integer',
            'birthday_month' => 'required|integer',
            'birthday_year' => 'required|integer',
            'sex' => 'required|in:Masculino,Femenino,others',
            'email' => 'required|email|unique:users,email|max:100',
            'password' => 'required|string|min:6|confirmed',
        ]);



        // Construcción de la fecha de nacimiento a partir de los datos proporcionados
        $birthday = Carbon::createFromDate(
            $validatedData['birthday_year'],
            $validatedData['birthday_month'],
            $validatedData['birthday_day']
        )->format('Y-m-d');



        try {
            // Llamada al procedimiento almacenado con datos validados y contraseña encriptada
            DB::statement('CALL Crear_Nuevo_usuario(?, ?, ?, ?, ?, ?)', [
                $validatedData['Nombres'],
                $validatedData['Apellidos'],
                $birthday,
                $validatedData['sex'],
                $validatedData['email'],
                $validatedData['password']
            ]);


            return redirect('/')->with('success', 'Registro exitoso');
        } catch (\Exception $e) {

            return back()->withErrors(['error' => 'Error al registrar el usuario: ' . $e->getMessage()]);
        }
    }
    public function Usuario()
    {
        return view('Usuario');
    }
    public function feed()
    {
        $publicaciones = DB::select("SELECT * FROM publicaciones ORDER BY fecha_publicacion DESC");
        return view('feed', compact('publicaciones'));
    }
    public function Solicitudes()
    {
        $userId = Auth::id(); // Obtenemos el ID del usuario autenticado

        // Llamada al procedimiento almacenado
        $solicitudes = DB::select('CALL ObtenerSolicitudesDeAmistadPendientes(?)', [$userId]);

        // Pasamos las solicitudes a la vista
        return view('solicitudes', compact('solicitudes'));
    }
}
