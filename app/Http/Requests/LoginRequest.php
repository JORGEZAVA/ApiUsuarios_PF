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
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "email"=>"required|email",
            "password"=> "required|string|min:4",
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
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
