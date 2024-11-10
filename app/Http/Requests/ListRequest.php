<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class ListRequest extends FormRequest
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
        $typeSchedule = $this->input('typeSchedule');

        $horaMarcada = $typeSchedule == 'HM';

        $array = [
            'user_id'           => 'required',
            'service_id'        => 'required',
            'establishment_id'  => 'required',
            'date'              => 'required|date',
            'time'              => $horaMarcada ? 'required' : '',
            'professional_id'   => 'required',
        ];


        return $array;
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
        return [
            'user_id.required'          => 'O campo usuário é obrigatório.',
            'service_id.required'       => 'O campo serviço é obrigatório.',
            'establishment_id.required' => 'O campo estabelecimento é obrigatório.',
            'date.required'             => 'O campo data é obrigatório.',
            'date.date'                 => 'O campo data não é uma data válida.',
            'time.required'             => 'O campo hora é obrigatório.',
            'time.date_format'          => 'O campo hora deve estar no formato HH:MM.',
            'professional_id.required'  => 'O campo profissional é obrigatório.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['message' => 'Erro de validação de atributo', 'error' => $validator->errors()], Response::HTTP_NOT_ACCEPTABLE));
    }
}
