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

    public function comentar(Request $request, $id)
    {
        $request->validate([
            'contenido' => 'required|string|max:500',
        ]);

        $contenido = $request->input('contenido');
        $userId = Auth::id();
        $publicacionId = $id;

        try {
            // Llamar al procedimiento almacenado
            DB::statement('CALL AgregarComentario(?, ?, ?, ?)', [
                $contenido,
                $userId,
                $publicacionId,
                now(),
            ]);

            return redirect()->back()->with('success', 'Comentario agregado con éxito.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ocurrió un error al agregar el comentario.');
        }
    }

    public function reaccionar(Request $request, $id)
    {
        $tipo = $request->input('tipo'); // "me gusta" o "me encanta"
        $userId = Auth::id();
        $publicacionId = $id;

        try {
            // Llamar al procedimiento almacenado
            DB::statement('CALL obtener_reacciones(?, ?, ?)', [
                $tipo,
                $userId,
                $publicacionId,
            ]);

            return redirect()->back()->with('success', 'Reacción procesada con éxito.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ocurrió un error al procesar la reacción.');
        }
    }

    public function Nuevofeed(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors('Debes iniciar sesión para publicar.');
        }

        // Validar los datos del formulario
        $request->validate([
            'content' => 'required|string|max:500',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $userId = Auth::id();
        $content = $request->input('content');
        $imagePath = null;


        if ($request->hasFile('imagen')) {

            try {
                $imageName = time() . '_' . $request->file('imagen')->getClientOriginalName();
                $request->file('imagen')->storeAs('public/posts', $imageName);
                $imagePath = 'posts/' . $imageName;
            } catch (\Exception $e) {
                return redirect()->back()
                    ->withErrors('Error al subir la imagen: ' . $e->getMessage())
                    ->withInput();
            }
        }

        try {

            DB::statement("CALL crear_publicacion(?, ?, ?, ?)", [
                $userId,
                $content,
                $imagePath,
                now(),
            ]);


            return redirect()->back()->with('success', 'Publicación creada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('Error al crear la publicación: ' . $e->getMessage())
                ->withInput();
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



    public function feed()
    {
        try {
            // Ejemplo: Obtener el usuario autenticado
            $usuario = Auth::user();

            // Verificar si hay un usuario autenticado
            if (!$usuario) {
                return redirect()->route('login')->with('error', 'Usuario no autenticado.');
            }

            // Obtener todas las publicaciones del usuario o generales
            $publicaciones = DB::select('CALL obtener_publicaciones()');

            $comentarios = [];
            $reacciones = [
                'me_gusta' => [],
                'me_encanta' => [],
            ];

            foreach ($publicaciones as $publicacion) {
                $idPublicacion = $publicacion->id_publicacion;

                // Obtener comentarios por publicación
                $comentarios[$idPublicacion] = DB::select('CALL obtener_comentarios(?)', [$idPublicacion]);

                // Obtener reacciones de "me gusta" y "me encanta"
                $reacciones['me_gusta'][$idPublicacion] = DB::select(
                    'CALL obtener_reacciones(?, ?, ?)',
                    ['me gusta', $usuario->id_usuario, $idPublicacion]
                );

                $reacciones['me_encanta'][$idPublicacion] = DB::select(
                    'CALL obtener_reacciones(?, ?, ?)',
                    ['me encanta', $usuario->id_usuario, $idPublicacion]
                );
            }

            /*dd([
                'publicaciones' => $publicaciones,
                'comentarios' => $comentarios,
                'reacciones' => $reacciones,
            ]);*/
            // Pasar los datos a la vista Blade
            return view('feed', [
                'publicaciones' => $publicaciones,
                'comentarios' => $comentarios,
                'reacciones' => $reacciones,
            ]);
        } catch (\Exception $e) {
            return redirect()->route('error')->with('error', 'Error al obtener datos de las publicaciones.');
        }
    }


    public function EnviarSolicitudes()
    {
        $userId = Auth::id();


        $users = User::where('id_usuario', '!=', $userId)
            ->whereNotIn('id_usuario', function ($query) use ($userId) {
                $query->select('id_usuario2')
                    ->from('amigos')
                    ->where('id_usuario1', $userId);
            })
            ->get();

        return view('EnviarSolicitudes', compact('users'));
    }

    public function enviarSolicitud($receiverId)

    {
        try {
            $senderId = Auth::id();

            $existingSolicitud = DB::table('solicitudes')
                ->where('enviado_id', $senderId)
                ->where('recivido_id', $receiverId)
                ->where('status', 'pendiente')
                ->first();

            if ($existingSolicitud) {
                return response()->json(['message' => 'Ya existe una solicitud pendiente'], 400);
            }


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
            ->where('solicitudes.status', 'pendiente')
            ->select('usuario.*')
            ->get();

        return view('Solicitudes', compact('requests'));
    }

    public function Solicitud($senderId)
    {
        try {
            $receiverId = Auth::id();


            DB::statement("SET @responseMessage = ''");
            DB::statement('CALL AceptarSolicitud(?, ?, @responseMessage)', [$senderId, $receiverId]);


            $response = DB::select('SELECT @responseMessage AS message');


            return response()->json(['message' => $response[0]->message]);
        } catch (\Exception $e) {

            return response()->json(['message' => 'Error accepting the request: ' . $e->getMessage()], 500);
        }
    }

    public function actualizarFotoPerfil(Request $request)
    {
        $request->validate([
            'foto_perfil' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $userId = Auth::id();

        try {
            if ($request->hasFile('foto_perfil')) {
                $imageName = time() . '_' . $request->file('foto_perfil')->getClientOriginalName();
                $request->file('foto_perfil')->storeAs('public/profile', $imageName);

                DB::table('usuario')
                    ->where('id_usuario', $userId)
                    ->update(['foto_perfil' => 'profile/' . $imageName]);

                return redirect()->back()->with('success', 'Foto de perfil actualizada exitosamente');
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Error al actualizar la foto: ' . $e->getMessage());
        }
    }

    public function actualizarFotoPortada(Request $request)
    {
        $request->validate([
            'foto_portada' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            if ($request->hasFile('foto_portada')) {
                $imageName = time() . '_portada_' . $request->file('foto_portada')->getClientOriginalName();
                $request->file('foto_portada')->storeAs('public/covers', $imageName);


                DB::table('usuario')
                    ->where('id_usuario', Auth::id())
                    ->update(['foto_portada' => 'covers/' . $imageName]);

                return redirect()->back()->with('success', 'Foto de portada actualizada exitosamente');
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Error al actualizar la portada: ' . $e->getMessage());
        }
    }

    public function editarPerfil(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:50',
            'apellidos' => 'required|string|max:100',
            'fecha_nacimiento' => 'required|date',
            'genero' => 'required|string|max:20',
            'email' => 'required|email|unique:usuario,email,' . Auth::id() . ',id_usuario'
        ]);

        try {
            DB::table('usuario')
                ->where('id_usuario', Auth::id())
                ->update([
                    'nombre' => $request->nombre,
                    'apellidos' => $request->apellidos,
                    'fecha_nacimiento' => $request->fecha_nacimiento,
                    'genero' => $request->genero,
                    'email' => $request->email
                ]);

            return redirect()->back()->with('success', 'Perfil actualizado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Error al actualizar el perfil: ' . $e->getMessage());
        }
    }

    public function Usuario(Request $request)
    {
        $userId = Auth::id();
        $tab = $request->query('tab', 'publicaciones');

        // Retrieve publications
        $publicaciones = DB::select("CALL Listar_Publicaciones(?)", [$userId]);

        // Convert publications to a collection
        $publicaciones = collect($publicaciones);

        // Retrieve friends
        $amigos = DB::table('amigos')
            ->join('usuario', function ($join) use ($userId) {
                $join->on('amigos.id_usuario1', '=', 'usuario.id_usuario')
                    ->where('amigos.id_usuario2', $userId)
                    ->orOn('amigos.id_usuario2', '=', 'usuario.id_usuario')
                    ->where('amigos.id_usuario1', $userId);
            })
            ->select('usuario.*')
            ->get();

        // Retrieve photos
        $fotos = DB::table('publicaciones')
            ->where('id_usuario', $userId)
            ->whereNotNull('imagen')
            ->select('imagen')
            ->get();

        // Return the view with all necessary data
        return view('Usuario', compact('publicaciones', 'amigos', 'fotos'));
    }
}
