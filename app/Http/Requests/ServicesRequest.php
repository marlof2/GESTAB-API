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
            "name" => "required|string|max:100|unique:services",
            "amount" => ["numeric", "regex:/^\d+(\.\d{1,2})?$/"],
            "time" => "nullable|string|date_format:H:i",
        ];
    }

    public function update()
    {
        $rules = ["name" => "required|string|max:100|unique:services", [
                Rule::exists('services')->where(function ($query) {
                    $query->where('name', $this->request->all()['name'])->where('id', '<>', $this->id_request);
                })
            ]];
        return $rules;
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
            "name.unique" => "O serviço informado já existe.",
            "amount.numeric" => "O campo informado deve ser do tipo texto.",
            "amount.regex" => "O valor deve conter duas casas decimais.",
            "time.string" => "O campo informado deve ser do tipo texto.",
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['message' => 'Erro de validação de atributo', 'error' => $validator->errors()], Response::HTTP_NOT_ACCEPTABLE));
    }
}
