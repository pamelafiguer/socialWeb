<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class redsocialController extends Controller
{
    public function Login()
    {
        return view('login');
    }

    public function buscar(Request $request)
    {
        $query = $request->input('query');
        $results = DB::select("CALL BuscarClientes(?)", [$query]);
        return response()->json($results);
    }

    public function Amigos()
    {

        $userId = Auth::id();
        $friends = DB::table('amigos')
            ->join('usuario', function ($join) use ($userId) {
                $join->on('amigos.id_usuario1', '=', 'usuario.id_usuario')
                    ->where('amigos.id_usuario2', $userId)
                    ->orOn('amigos.id_usuario2', '=', 'usuario.id_usuario')
                    ->where('amigos.id_usuario1', $userId);
            })
            ->select('usuario.*')
            ->get();

        return view('amigos', compact('friends'));
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
                $imagePaths[] = str_replace('/storage', '', $path);
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

    public function LoginForm(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $usuario = DB::select("CALL Usuario_Login(?, ?)", [$request->email, $request->password]);

        if (count($usuario) > 0) {
            $usuario = User::find($usuario[0]->id_usuario);
            if ($usuario) {
                Auth::login($usuario);
                return redirect('/feed');
            } else {
                return back()->withErrors(['email' => 'Usuario no encontrado.']);
            }
        } else {
            return back()->withErrors(['email' => 'Credenciales incorrectas.']);
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

        $birthday = Carbon::createFromDate(
            $validatedData['birthday_year'],
            $validatedData['birthday_month'],
            $validatedData['birthday_day']
        )->format('Y-m-d');

        try {
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

    public function EnviarSolicitudes()
    {
        $userId = Auth::id();

        // Obtener los usuarios que no son amigos
        $users = User::where('id_usuario', '!=', $userId) // Excluir al usuario autenticado
            ->whereNotIn('id_usuario', function ($query) use ($userId) {
                $query->select('id_usuario2')
                    ->from('amigos')
                    ->where('id_usuario1', $userId);
            })
            ->get(); // Obtener todos los usuarios no amigos

        return view('EnviarSolicitudes', compact('users'));
    }

    public function enviarSolicitud($receiverId)

    {
        try {
            $senderId = Auth::id(); // Obtener el ID del usuario autenticado

            // Verificar si ya existe una solicitud
            $existingSolicitud = DB::table('solicitudes')
                ->where('enviado_id', $senderId)
                ->where('recivido_id', $receiverId)
                ->where('status', 'pendiente')
                ->first();

            if ($existingSolicitud) {
                return response()->json(['message' => 'Ya existe una solicitud pendiente'], 400);
            }

            // Llamar al procedimiento almacenado
            DB::statement('CALL EnviarSolicitud(?, ?)', [$senderId, $receiverId]);

            return response()->json(['message' => 'Solicitud enviada exitosamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al enviar la solicitud: ' . $e->getMessage()], 500);
        }
    }




    public function listFriends()
    {

        $userId = Auth::id();
        $friends = DB::table('amigos')
            ->join('usuario', function ($join) use ($userId) {
                $join->on('amigos.id_usuario1', '=', 'usuario.id_usuario')
                    ->where('amigos.id_usuario2', $userId)
                    ->orOn('amigos.id_usuario2', '=', 'usuario.id_usuario')
                    ->where('amigos.id_usuario1', $userId);
            })
            ->select('usuario.*')
            ->get();

        return view('amigos', compact('friends'));
    }

    public function receivedRequests()
    {
        $userId = Auth::id();
        $requests = DB::table('solicitudes')
            ->join('usuario', 'solicitudes.enviado_id', '=', 'usuario.id_usuario')
            ->where('solicitudes.recivido_id', $userId)
            ->where('solicitudes.status', 'pendiente') // Solo solicitudes pendientes
            ->select('usuario.*')
            ->get();

        return view('Solicitudes', compact('requests'));
    }

    public function Solicitud($senderId)
    {
        try {
            $receiverId = Auth::id(); // ID del usuario autenticado

            // Ejecutar el procedimiento almacenado
            DB::statement("SET @responseMessage = ''");
            DB::statement('CALL AceptarSolicitud(?, ?, @responseMessage)', [$senderId, $receiverId]);

            // Obtener el mensaje de respuesta de la base de datos
            $response = DB::select('SELECT @responseMessage AS message');

            // Retornar el mensaje al cliente
            return response()->json(['message' => $response[0]->message]);
        } catch (\Exception $e) {
            // Manejar errores y devolver un mensaje al cliente
            return response()->json(['message' => 'Error accepting the request: ' . $e->getMessage()], 500);
        }
    }
}
