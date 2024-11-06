<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;


class redsocialController extends Controller
{
    public function Login() {
        return view('Login');
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
            
            session(['usuario' => $usuario[0]->id_usuario]);
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
        return view('feed'); 
    }
}
