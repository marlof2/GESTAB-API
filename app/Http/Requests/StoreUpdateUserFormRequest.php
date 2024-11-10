<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule; // Importação do Rule

class StoreUpdateUserFormRequest extends FormRequest
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
        return [
            'profile_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'cpf' => 'required|string|unique:users,cpf',
            'phone' => 'required|string|max:15|regex:/^\(\d{2}\) \d{4,5}-\d{4}$/',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'type_schedule' => 'nullable|string|size:2',
        ];
    }

	public function update()
	{
		return [
            'profile_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'cpf' => [
                'required',
                'string',
                Rule::unique('users', 'cpf')->ignore($this->id_request), // Ignorar CPF atual
            ],
            'phone' => 'required|string|max:15',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->id_request), // Ignorar e-mail atual
            ],
            'password' => 'required|string|min:6',
            'type_schedule' => 'nullable|string|size:2',
        ];
    }

	public function patch()
	{
        return [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'type_schedule' => 'nullable|string|size:2',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->id_request), // Ignorar e-mail atual
            ],
        ];
	}

	public function messages()
	{
        return [
            'profile_id.required' => 'O campo profile_id é obrigatório.',
            'profile_id.integer' => 'O campo profile_id deve ser um número inteiro.',
            'name.required' => 'O nome é obrigatório.',
            'name.max' => 'O nome não pode ter mais que 255 caracteres.',
            'cpf.required' => 'O CPF é obrigatório.',
            'cpf.unique' => 'Este CPF já esta sendo usado.',
            'phone.required' => 'O número de telefone é obrigatório.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'O formato do e-mail é inválido.',
            'email.unique' => 'Este e-mail já esta sendo usado.',
            'password.required' => 'A senha é obrigatória.',
            'password.min' => 'A senha deve ter no mínimo 6 caracteres.',
        ];
	}

	protected function failedValidation(Validator $validator)
	{
		throw new HttpResponseException(response()->json(['message' => 'Erro de validação de atributo','error' => $validator->errors()], Response::HTTP_NOT_ACCEPTABLE));
	}
}
