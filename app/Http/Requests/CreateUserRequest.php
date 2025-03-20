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
