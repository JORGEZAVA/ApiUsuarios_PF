<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool{
        return true;
    }

    public function rules(): array{
        return [
            "email"=>"required|email",
            "password"=> "required|string|min:4",
        ];
    }

    public function attributes(): array{
        return [
            'email' => 'correo',
            'password' => 'contraseña',
        ];
    }

    public function messages(): array{
        return [
            "required" => "El campo :attribute es requerido",
            "email.email" => "El campo :attribute debe ser un correo electronico",
            "password.string" => "El campo :attribute debe ser un texto",
            "password.min" => "El campo :attribute debe tener al menos :min caracteres",
        ];
    }

    protected function failedValidation(Validator $validator): void{
        $errors = $validator->errors();

        $data=[
            'mensaje' => 'Validacion fallada',
            'errors' => $errors,
            'status' => 422,
        ];
        $response = response()->json($data, 422);

        throw new ValidationException($validator, $response);
    }
}
