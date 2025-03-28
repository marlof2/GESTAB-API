<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class LoginFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => ["required", "string"],
            'password' => ["required","string"]
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Campo Obrigatório!.',
            'password.required' => 'Campo Obrigatório!.',
        ];
    }


    protected function failedValidation(Validator $validator)
    {

        throw new HttpResponseException(
            response()->json(['error' => $validator->errors(), 'tipo' => 'Validação de Campo'], Response::HTTP_NOT_ACCEPTABLE)
        );
    }
}
