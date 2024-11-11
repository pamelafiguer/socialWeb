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



class redsocialController extends Controller
{

    
    public function Login() {
        return view('login');
    }

    public function Amigos() {
        return view('Amigos');
    }

    public function videos() {
        return view('videos');
    }

    public function Nuevofeed(Request $request)
    {
        
        $request->validate([
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048'
        ]);
        
        


        $content = $request->content;
        $imagePath = null;


        // Si el usuario ha subido una imagen, almacenarla en el directorio 'public/posts'
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('public/posts');
            $imagePath = str_replace('public/', '', $imagePath); 
        }

        DB::statement("CALL Crear_Publicacion(?, ?, ?, ?)", [
            session('id_usuario'),
            $content,
            $imagePath,
            now()->toDateString() 
        ]);

        if (!empty($resultado) && isset($resultado[0]->user_id)) {
            $userId = $resultado[0]->user_id;
        }

        return redirect()->back()->with('success', 'Publicación creada exitosamente.');
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

    public function LoginForm(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $correo = $request->input('email');
        $password = $request->input('password');

        $usuario = DB::select('call Usuario_Login(?,?)', [$correo, $password]) ;

        if ($usuario && $correo === $usuario[0]->email && $password === $usuario[0]->passwordd) {
            
            session([
                'usuario' => $usuario[0]->id_usuario,
                'usuario_nombre' => $usuario[0]->nombre]);
                
            return redirect('/feed')->with('success', 'Ingreso exitoso');
            
        }
    }
    public function Register() {
        return view('Register');
    }
    public function RegisterForm(Request $request) {
    
            $request->validate([
                'Nombres' => 'required',
                'Apellidos' => 'required',
                'birthday_day' => 'required',
                'birthday_month' => 'required',
                'birthday_year' => 'required'. now()->year,
                'sex' => 'required',
                'email' => 'required',
                'password' => 'required'
    
            ]);

            $birthday = Carbon::createFromDate(
                $request->input('birthday_year'),
                $request->input('birthday_month'),
                $request->input('birthday_day'),
            )->format('Y-m-d');

            
            DB::statement('call Crear_Nuevo_usuario(?,?,?,?,?,?)', 
            [
                $request->input('Nombres'),
                $request->input('Apellidos'),
                $birthday,
                $request->input('sex'),
                $request->input('email'),
                $request->input('password'),

            ]);
            return redirect('/feed')->with('success', 'Registro exitoso');
        
    }
    public function Usuario() {
        return view('Usuario');
    }
    public function feed()
    {
        $publicaciones = DB::select("SELECT * FROM publicaciones ORDER BY fecha_publicacion DESC");
        return view('feed', compact('publicaciones'));
        
    }
}
