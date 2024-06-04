<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class EstablishmentRequest extends FormRequest
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
            'name' => 'required|string|max:100',
            'type_of_person_id' => 'required|integer',
            'cpf' => [
                'required_if:type_of_person_id,1',
            ],
            'cnpj' => [
                'required_if:type_of_person_id,2',
            ],
        ];
    }

    public function update()
    {
        return [
            'name' => 'required|string|max:100',
            'type_of_person_id' => 'required|integer',
            'cpf' => [
                'required_if:type_of_person_id,1',
            ],
            'cnpj' => [
                'required_if:type_of_person_id,2',
            ],
        ];
    }

    public function view()
    {
        return [];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nome é obrigatório',
            'type_of_person_id.required' => 'Tipo de pessoa é obrigatório',
            'cpf.required' => 'CPF é obrigatório',
            'cnpj.required' => 'CNPJ é obrigatório',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['message' => 'Erro de validação de atributo', 'error' => $validator->errors()], Response::HTTP_NOT_ACCEPTABLE));
    }
}
