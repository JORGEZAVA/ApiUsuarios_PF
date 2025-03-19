<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/usuarios', [UserController::class, "index"]);

Route::delete('/usuarios/{id}', [UserController::class, "destroy"]);

Route::put("/usuarios/{id}", [UserController::class, "update"]);

Route::patch("/usuarios/{id}", [UserController::class, "updatePartial"]);

Route::get('/usuarios/{id}', [UserController::class, "show"]);

Route::post("/usuarios",[UserController::class, "store"]);