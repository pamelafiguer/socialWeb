<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class redsocialController extends Controller
{
    public function Login() {
        return view('Login');
    }
    public function Register() {
        return view('Register');
    }
}
