<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class ServicesRequest extends FormRequest
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
            "name" => ["required", "string", "max:100", "unique:services"],
            "amount" => ["nullable", "numeric", "regex:/^\d+(\.\d{1,2})?$/"],
            "time" => ["nullable"],
        ];
    }

    public function update()
    {
       return [
            "name" => ["required", "string", "max:100", "unique:services,name,$this->id_request,id"],
            "amount" => ["nullable", "numeric", "regex:/^\d+(\.\d{1,2})?$/"],
            "time" => ["nullable"],
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
            "name.max" => "Máximo de 100 caracteres.",
            "name.unique" => "O nome do serviço já existe para este estabelecimento.",
            "amount.numeric" => "O campo informado deve ser do tipo texto.",
            "amount.regex" => "O valor deve conter duas casas decimais.",
            // "time.date_format" => "O tempo informado deve ser no formato HH:mm.",
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['message' => 'Erro de validação de atributo', 'error' => $validator->errors()], Response::HTTP_NOT_ACCEPTABLE));
    }
}
