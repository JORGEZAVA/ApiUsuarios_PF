<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class UpdateUserRequest extends FormRequest{
    public function authorize(): bool{
        return true;
    }

    public function rules(): array{
        return [
            "name"=> "alpha|min:3|max:255|string",
            "email"=> "email|unique:users,email," . $this->route('id'),
            "password"=> "string|min:4",
            "biografia"=> "string|min:3|max:255",
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function attributes(): array{
        return [
            'name' => 'nombre',
            'email' => 'correo',
            'password' => 'contraseña',
            "biografia" => "biografia",
            'image' => 'imagen',
        ];
    }

    public function messages(): array{
        return [

            "name.min" => "El campo :attribute debe tener al menos :min caracteres",
            "name.max" => "El campo :attribute debe tener al menos :max caracteres",
            "name.string" => "El campo :attribute debe ser un texto",
            "name.alpha" => "El campo :attribute debe ser un texto",

            "email.email" => "El campo :attribute debe ser un correo electronico",
            "email.unique" => "El campo :attribute debe ser un correo electronico unico",

            "password.string" => "El campo :attribute debe ser un texto",
            "password.min" => "El campo :attribute debe tener al menos :min caracteres",

            "biografia.max" => "El campo :attribute debe ser como maximo :max caracteres",
            "biografia.min" => "El campo :attribute debe ser como minimo :min caracteres",

            "image.image" => "El campo :attribute debe ser una imagen",
            "image.mimes" => "El campo :attribute debe ser una imagen con extensiones jpeg,png,jpg,gif,svg",
            "image.max" => "El campo :attribute debe ser una imagen con un tamaño maximo de 2MB",
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        $response = response()->json([
            'message' => 'Validacion fallada',
            'errors' => $errors,
            'status' => 422,
        ], 422);

        throw new ValidationException($validator, $response);
    }
}
