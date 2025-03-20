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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
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

        
        $response = response()->json([
            'message' => 'Validacion fallada',
            'errors' => $errors,
            'status' => 422,
        ], 422);

        throw new ValidationException($validator, $response);
    }
}
