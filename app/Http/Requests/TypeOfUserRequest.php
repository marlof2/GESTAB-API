<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class TypeOfUserRequest extends FormRequest
{
    public $id_request;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $this->id_request = $this->route('id');
        return match ($this->method()) {
            'POST' => $this->store(),
            'PUT', 'PATCH' => $this->update(),
            default => $this->view()
        };
    }

    public function store()
    {
        return [
            "name" => ["required", "string", "max:255", "unique:type_of_user"]
        ];
    }

    public function update()
    {
        return [
            "name" => ["required", "string", "max:100", "unique:type_of_user,name,$this->id_request,id"],
        ];
    }

    public function view()
    {
        return [];
    }

    public function messages()
    {
        return [
            "name.required" => "O campo é obrigatório.",
            "name.string" => "O campo informado deve ser do tipo texto.",
            "name.max" => "Máximo de 255 caracteres.",
            "name.unique" => "Nome informado já existe.",
            "name.exists" => "Nome informado já existe.",
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['message' => 'Erro de validação de atributo', 'error' => $validator->errors()], Response::HTTP_NOT_ACCEPTABLE));
    }
}
