<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, "iniciarSesion"]);

Route::get('/usuarios', [UserController::class, "index"]);

Route::get('/usuarios/{id}', [UserController::class, "show"]);

Route::post("/usuarios",[UserController::class, "store"]);

Route::middleware("auth:sanctum")->group(function(){

    Route::post('/logout', [AuthController::class, "desloguearse"]);

    Route::delete('/usuarios/{id}', [UserController::class, "destroy"]);

    Route::put("/usuarios/{id}", [UserController::class, "update"]);

    Route::patch("/usuarios/{id}", [UserController::class, "updatePartial"]);

    Route::middleware("admin")->group(function(){
        
        Route::get('/admin', function () {
            return response()->json(["mensaje"=>"Bienvenido al panel de administracion"],200);
        });
    
        Route::put('/usuarios/{id}/admin', [UserController::class, "makeAdmin"]);
    
        Route::put('/usuarios/{id}/ban', [UserController::class, "banUser"]);

        Route::put('/usuarios/{id}/desban', [UserController::class, "desbanUser"]);
    
    });
    
});

Route::middleware('auth:sanctum')->get('/validarToken', function (Request $request) {
    $usuario= Auth::user();
    return response()->json([
        'message' => 'Token válido',
        'user' => $usuario, 
    ]);
});




