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

    public function obtenerAmigos()
    {
        
        $usuarioId = Auth::id();

        
        $amigos = DB::select('CALL ObtenerAmigosporID(?)', [$usuarioId]);

        
        return response()->json($amigos);
    }

    public function videos()
    {
        return view('videos');
    }

    public function comentar(Request $request, $id_publicacion)
    {
        $contenido = $request->input('contenido');
        $idUsuario = $request->input('id_usuario');

        try {
            
            DB::statement('CALL Agregar_Comentario(?, ?, ?, ?)', [
                $id_publicacion,
                $idUsuario,
                $contenido,
                now()->toDateString() 
            ]);
            return back()->with('success', 'Comentario agregado correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Hubo un problema al agregar el comentario: ' . $e->getMessage());
        }
    }

    public function reaccionar(Request $request, $idPublicacion)
    {
        $idUsuario = Auth::id();
        $tipoReaccion = $request->input('tipo'); 

        try {
            
            DB::statement('CALL agregar_reacciones(?, ?, ?)', [$tipoReaccion, $idUsuario, $idPublicacion]);

            
            $meGusta = DB::table('likes')->where('id_publicacion', $idPublicacion)->where('reaccion', 'me gusta')->count();
            $meEncanta = DB::table('likes')->where('id_publicacion', $idPublicacion)->where('reaccion', 'me encanta')->count();

            return back()->with('success', 'Comentario agregado correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Hubo un problema al agregar el comentario: ' . $e->getMessage());
        }
    }

    public function Nuevofeed(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors('Debes iniciar sesión para publicar.');
        }

        
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
            
            $usuario = Auth::user();

            
            if (!$usuario) {
                return redirect()->route('login')->with('error', 'Usuario no autenticado.');
            }

            
            $publicaciones = DB::select('CALL obtener_publicaciones()');
            foreach ($publicaciones as $publicacion) {
                $reaccion = DB::table('likes')
                    ->where('id_usuario', $usuario)
                    ->where('id_publicacion', $publicacion->id_publicacion)
                    ->value('reaccion'); 

                $publicacion->reaccion_usuario = $reaccion;
            }

            $comentarios = [];
            $reacciones = [];

            foreach ($publicaciones as $publicacion) {
                $idPublicacion = $publicacion->id_publicacion;

                
                $comentarios[$idPublicacion] = DB::select('CALL obtener_comentarios(?)', [$idPublicacion]);

                
                $reacciones[$idPublicacion] = DB::select('CALL ObtenerReacciones(?)', [$idPublicacion]);
            }

            
            return view('feed', [
                'publicaciones' => $publicaciones,
                'comentarios' => $comentarios,
                'reacciones' => $reacciones, 
            ]);
            dd([
                'publicaciones' => $publicaciones,
                'comentarios' => $comentarios,
                'reacciones' => $reacciones,
            ]);
        
    }

    public function obtenerReacciones($id_publicacion)
    {
        try {
            
            $reacciones = DB::select('CALL ObtenerReacciones(?)', [$id_publicacion]);

            
            return response()->json($reacciones);
        } catch (\Exception $e) {
            return response()->json(['error' => 'No se pudieron obtener las reacciones'], 500);
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

        
        $publicaciones = DB::select("CALL Listar_Publicaciones(?)", [$userId]);

        
        $publicaciones = collect($publicaciones);

        
        $amigos = DB::table('amigos')
            ->join('usuario', function ($join) use ($userId) {
                $join->on('amigos.id_usuario1', '=', 'usuario.id_usuario')
                    ->where('amigos.id_usuario2', $userId)
                    ->orOn('amigos.id_usuario2', '=', 'usuario.id_usuario')
                    ->where('amigos.id_usuario1', $userId);
            })
            ->select('usuario.*')
            ->get();

        
        $fotos = DB::table('publicaciones')
            ->where('id_usuario', $userId)
            ->whereNotNull('imagen')
            ->select('imagen')
            ->get();

        
        return view('Usuario', compact('publicaciones', 'amigos', 'fotos'));
    }

    public function buscarUsuarioPorNombre(Request $request)
    {
        
        $nombre = $request->query('query'); 

        if (!$nombre || strlen($nombre) < 3) {
            return response()->json(['error' => 'El término de búsqueda debe tener al menos 3 caracteres'], 400);
        }

        try {
            $usuario = DB::select('CALL BuscarClientes(?)', [$nombre]);

            
            if (empty($usuario)) {
                return response()->json(['error' => 'Usuario no encontrado'], 404);
            }

            
            return response()->json($usuario);
        } catch (\Exception $e) {
            
            return response()->json(['error' => 'Error al realizar la búsqueda', 'message' => $e->getMessage()], 500);
        }
    }

    public function PerfilUsuario($id_usuario)
    {
        
        $usuario = DB::select('CALL ObtenerPerfilUsuario(?)', [$id_usuario]);

        $publicaciones = DB::select('CALL Listar_Publicaciones(?)', [$id_usuario]);

        $amigos = DB::select('CALL ObtenerAmigosporID(?)', [$id_usuario]);
        
        $fotos = DB::select('CALL ObtenerFotosporID(?)', [$id_usuario]);

        $usuario = (object) $usuario[0];

        return view('perfil_usuario', compact('usuario', 'publicaciones', 'amigos', 'fotos'));
    }
}
