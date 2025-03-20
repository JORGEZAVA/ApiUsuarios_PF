<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function iniciarSesion(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        $usuario = User::where('email', $credentials['email'])->first();

        $confirmacionContraseña=Hash::check($credentials['password'], $usuario->password);

        if (!$usuario || !$confirmacionContraseña) {
            return response()->json(["mensaje" => "Credenciales incorrectas"], 401);
        }

        // Crea un token de autenticacion y lo pasa en formato de texto plano
        $token = $usuario->createToken('TOKEN API')->plainTextToken;

        $data=[
            "mensaje"=>"Inicio de sesión exitoso",
            "status"=>200,
            "token"=>$token,
        ];

        return response()->json($data, 200);
    }

    public function desloguearse(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['mensaje' => 'Sesión cerrada']);
    }
}

