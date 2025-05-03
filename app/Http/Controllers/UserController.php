<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Pail\ValueObjects\Origin\Console;

class UserController extends Controller
{
    
    public function index()
    {
        $usuarios= User::paginate(10);

        $data=[
            "mensaje"=> "Usuarios obtenidos con exito",
            "status"=>200,
            "usuarios"=> $usuarios,
        ];

        return response()->json($data, 200);
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
            "biografia"=> "string|max:255",
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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
        $usuario->password=Hash::make($request->password) ;
        $usuario->biografia=$request->biografia;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            // Leer el contenido del archivo
            $imageData = file_get_contents($file->getRealPath());
            $imageData = base64_encode($imageData);
            $usuario->image=$imageData;
        } else {
            $imageData = null;
        }

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
            "email"=> "email|unique:users,email," . $usuario->id, // Ha excepcion del usuario que se esta editando
            "password"=> "string|min:4",
            "biografia"=> "string|max:255",
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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
            $usuario->password=Hash::make($request->password) ;
        }
        if($request->has("biografia")){
            $usuario->biografia=$request->biografia;
        }
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            // Leer el contenido del archivo
            $imageData = file_get_contents($file->getRealPath());
            // Convertir a base64
            $imageData = base64_encode($imageData);
            $usuario->image=$imageData;
        } 

        $usuario->save();

        $data=[
            "mensaje"=>"El usuario ha sido actualizado con exito",
            "status"=>200,
            "resultado"=>$request->all(),
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
        if($usuario->role == "admin"){
            $data=[
                "mensaje"=> "El usuario ya es administrador",
                "status"=> 400,
            ];
            return response()->json($data,400);
        }
        $usuario->role="admin";
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

        if($usuario->is_banned){
            $data=[
                "mensaje"=> "El usuario ya esta baneado",
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

    public function desbanUser($identificador)
    {
        $usuario=User::find($identificador);

        if(!$usuario){
            $data=[
                "mensaje"=> "Usuario no encontrado",
                "status"=> 400,
            ];
            return response()->json($data,400);
        }
        if($usuario->is_banned==false){
            $data=[
                "mensaje"=> "Usuario no tiene un ban activo",
                "status"=> 400,
            ];
            return response()->json($data,400);
        }

        $usuario->is_banned=false;
        $usuario->save();

        $data=[
            "mensaje"=>"El usuario ha sido desbaneado con exito",
            "status"=>200,
            
        ];
        return response()->json($data,200);
    }
}
