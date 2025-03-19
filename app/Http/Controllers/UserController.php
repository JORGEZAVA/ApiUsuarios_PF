<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    
    public function index()
    {
       $usuarios= User::all();
       return response()->json($usuarios, 200);
    }

   
    public function store(Request $request)
    {
       $validator=Validator::make($request->all(),[
            "name"=> "required|min:3|max:255|string",
            "email"=> "required|email|unique:users,email",
            "password"=> "required|string|min:4",
       ]);

       if($validator->fails()){
            $data=[
                "mensaje"=> "Error en la validacion de datos",
                "errores"=> $validator->errors(),
            ];
            return response()->json($data,400);
       }

       $usuario= User::create([
            "name"=>$request->name,
            "email"=>$request->email,
            "password"=>$request->password,
       ]);

       if(!$usuario){
            $data=[
                "mensaje"=>"Error al crear usuario",
                "status"=> 500,
            ];
            return response()->json($data,500);
       }

       return response()->json($usuario,201);

    }

   
    public function show($identificador)
    {
        $usuario= User::find($identificador);

        if(!$usuario){
            $data=[
                "mensaje"=> "Usuario no encontrado",
                "status"=> 400,
            ];
            return response()->json($data,400);
        }

        return response()->json($usuario,200);
        
    }

    
    public function update(Request $request, $identificador)
    {
        $usuario=User::find($identificador);
        if(!$usuario){
            $data=[
                "mensaje"=> "Usuario no encontrado",
                "status"=> 400,
            ];
            return response()->json($data,400);
        }
        $validator=Validator::make($request->all(),[
            "name"=> "required|alpha|min:3|max:255|string",
            "email"=> "required|email|unique:users,email," . $usuario->id,
            "password"=> "required|string|min:4",
        ]);

        if($validator->fails()){
            $data=[
                "mensaje"=> "Error en la validacion de datos",
                "errores"=> $validator->errors(),
            ];
            return response()->json($data,400);
       }

       $usuario->name=$request->name;
       $usuario->email=$request->email;
       $usuario->password=$request->password;

       $usuario->save();

       $data=[
        "mensaje"=>"El usuario ha sido actualizado con exito",
        "status"=>200,
       ];
       return response()->json($data,200);
    }

   
    public function destroy($identificador)
    {
        $usuario= User::find($identificador);
        if(!$usuario){
            $data=[
                "mensaje"=> "Usuario no encontrado",
                "status"=> 400,
            ];
            return response()->json($data,400);
        }
        $usuario->delete();

        $data=[
            "mensaje"=>"Usuario eliminado con exito",
            "status"=> 200,
        ];
        return response()->json($data,200);
    }

    public function updatePartial(Request $request, $identificador)
    {
        $usuario=User::find($identificador);
        if(!$usuario){
            $data=[
                "mensaje"=> "Usuario no encontrado",
                "status"=> 400,
            ];
            return response()->json($data,400);
        }
        $validator=Validator::make($request->all(),[
            "name"=> "alpha|min:3|max:255|string",
            "email"=> "email|unique:users,email," . $usuario->id,
            "password"=> "string|min:4",
        ]);

        if($validator->fails()){
            $data=[
                "mensaje"=> "Error en la validacion de datos",
                "errores"=> $validator->errors(),
            ];
            return response()->json($data,400);
       }

      if($request->has("name")){
        $usuario->name=$request->name;
      }
      if($request->has("email")){
        $usuario->email=$request->email;
      }
      if($request->has("password")){
        $usuario->password=$request->password;
      }

      $usuario->save();

       $data=[
        "mensaje"=>"El usuario ha sido actualizado con exito",
        "status"=>200,
       ];
       return response()->json($data,200);
       
    }
}
