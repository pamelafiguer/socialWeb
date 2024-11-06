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
