<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Administrador
{
    public function handle(Request $request, Closure $next): Response
    {
        $usuario = Auth::user();
        if( !$usuario || $usuario->role != "admin"){
            return response()->json(["mensaje"=>"No tienes permisos para acceder a esta ruta"],403);
        }
        return $next($request);
    }
}
