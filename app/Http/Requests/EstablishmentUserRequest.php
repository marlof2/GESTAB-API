<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class EstablishmentUserRequest extends FormRequest
{
    public $id_request;

	public function authorize()
	{
		return true;
	}

	public function rules()
	{
        $this->id_request = $this->route('id');
		$method = $this->method();
		if ($method === 'POST') {
			return $this->store();
		} elseif ($method === 'PUT') {
			return $this->update();
		} elseif ($method === 'PATCH') {
			return $this->patch();
		} else {
			return $this->view();
		}
	}

	public function store()
	{
		return [];
    }

	public function update()
	{
		return [];
    }

	public function view()
	{
		return [];
	}

	public function messages()
	{
		return [];
	}

	protected function failedValidation(Validator $validator)
	{
		throw new HttpResponseException(response()->json(['message' => 'Erro de validação de atributo','error' => $validator->errors()], Response::HTTP_NOT_ACCEPTABLE));
	}
}
