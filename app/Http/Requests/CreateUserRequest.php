<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class CreateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "name"=> "required|min:3|max:255|string",
            "email"=> "required|email|unique:users,email",
            "password"=> "required|string|min:4",
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nombre',
            'email' => 'correo',
            'password' => 'contraseña',
        ];
    }

    public function messages(): array{
        return [
            "required" => "El campo :attribute es requerido",

            "name.min" => "El campo :attribute debe tener al menos :min caracteres",
            "name.max" => "El campo :attribute debe tener al menos :max caracteres",
            "name.string" => "El campo :attribute debe ser un texto",

            "email.email" => "El campo :attribute debe ser un correo electronico",
            "email.unique" => "El campo :attribute debe ser un correo electronico unico",

            "password.string" => "El campo :attribute debe ser un texto",
            "password.min" => "El campo :attribute debe tener al menos :min caracteres",
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
