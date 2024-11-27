<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class AuthController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function login (Request $request){
        //Validar los parámetros de entrada
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $token = Auth::attempt($request->only('email', 'password'));
        if (!$token){
            return response([
                'message'=>'Credenciales incorrectas',
                'data' => [],
                'error' => true
            ], 401);
        }


        $user = Auth::user();
        return response([
            'message'=>'Inicio de sesión exitoso',
            'data' => [
                'token' => $token,
                'user' => $user,
            ],
            'error' => false
        ],200);
    }

    public function logout (Request $request){
        Auth::logout();
        return response([
            'message'=>'Sesión cerrada',
            'data' => [],
            'error' => false
        ],200);
    }

    public function UserLogged (){
        return response([
            'message'=>'Usuario autenticado',
            'data' => [
                'user'=> Auth::user()
            ],
            'error' => false
        ],200);
    }
    
}