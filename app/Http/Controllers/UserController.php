<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    
    public function index()
    {
        $usuarios= User::paginate(10);
        return response()->json($usuarios, 200);
    }

    public function store(CreateUserRequest $request)
    {
        $usuario= User::create([
                "name"=>$request->name,
                "email"=>$request->email,
                "password"=> Hash::make($request->password) ,
        ]);

        if(!$usuario){
            $data=[
                "mensaje"=>"Error al crear usuario",
                "status"=> 500,
            ];
            return response()->json($data,500);
        }

        $data=[
            "mensaje"=>"Usuario creado con exito",
            "status"=>201,
            "token"=>$usuario->createToken('TOKEN API')->plainTextToken,
        ];

        return response()->json($data,201);

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

    public function makeAdmin($identificador)
    {
        $usuario=User::find($identificador);
        if(!$usuario){
            $data=[
                "mensaje"=> "Usuario no encontrado",
                "status"=> 400,
            ];
            return response()->json($data,400);
        }
        $usuario->rol="admin";
        $usuario->save();

        $data=[
            "mensaje"=>"El usuario ha sido convertido en administrador con exito",
            "status"=>200,
        ];
        return response()->json($data,200);
    }

    public function banUser($identificador)
    {
        $usuario=User::find($identificador);
        if(!$usuario){
            $data=[
                "mensaje"=> "Usuario no encontrado",
                "status"=> 400,
            ];
            return response()->json($data,400);
        }
        $usuario->is_banned=true;
        $usuario->save();

        $data=[
            "mensaje"=>"El usuario ha sido baneado con exito",
            "status"=>200,
        ];
        return response()->json($data,200);
    }
}
