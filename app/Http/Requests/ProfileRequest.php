<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class ProfileRequest extends FormRequest
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
            "name" => ["required", "string", "max:255", "unique:profiles"],
            "description" => ["required", "string", "max:255"]
        ];
    }

	public function update()
	{
        return [
            "name" => ["required", "string", "max:255", "unique:profiles,name,$this->id_request,id"],
            "description" => ["required", "string", "max:255"],
        ];
    }

	public function view()
	{
		return [];
	}

	public function messages()
	{
		return [
            "name.required" => "Nome é obrigatório",
            "name.unique" => "Já existe um perfil com o nome informado",
            "name.max" => "O tamanho máximo do nome é de 255 caracteres.",
            "description.required" => "Descrição é obrigatório",
            "description.max" => "O tamanho máximo da descrição é de 255 caracteres.",
        ];
	}

	protected function failedValidation(Validator $validator)
	{
		throw new HttpResponseException(response()->json(['message' => 'Erro de validação de atributo','error' => $validator->errors()], Response::HTTP_NOT_ACCEPTABLE));
	}
}
