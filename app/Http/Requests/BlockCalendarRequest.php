<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class BlockCalendarRequest extends FormRequest
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
            'blocks' => 'required|array',
            'blocks.*.establishment_id' => 'required',
            'blocks.*.user_id' => 'required',
            'blocks.*.period' => 'required|string|in:allday,morning,afternoon,night,personalized',
            'blocks.*.date' => 'required|date',
            'blocks.*.time_start' => 'required_if:blocks.*.period,personalized',
            'blocks.*.time_end' => 'required_if:blocks.*.period,personalized',
        ];
    }

	public function update()
	{
		return [
            'blocks' => 'required|array',
            'blocks.*.establishment_id' => 'required',
            'blocks.*.user_id' => 'required',
            'blocks.*.period' => 'required|string|in:allday,morning,afternoon,night,personalized',
            'blocks.*.date' => 'required|date',
            'blocks.*.time_start' => 'required_if:blocks.*.period,personalized',
            'blocks.*.time_end' => 'required_if:blocks.*.period,personalized',
        ];
    }

	public function view()
	{
		return [];
	}

	public function messages()
	{
		return [
            'blocks.required' => 'É necessário enviar pelo menos um bloqueio',
            'blocks.array' => 'O formato dos bloqueios deve ser um array',
            'blocks.*.establishment_id.required' => 'O campo estabelecimento é obrigatório para todos os bloqueios',
            'blocks.*.user_id.required' => 'O campo usuário é obrigatório para todos os bloqueios',
            'blocks.*.period.required' => 'O campo período é obrigatório para todos os bloqueios',
            'blocks.*.period.string' => 'O campo período deve ser uma string para todos os bloqueios',
            'blocks.*.period.in' => 'O período deve ser: personalizado, manhã, tarde ou noite para todos os bloqueios',
            'blocks.*.date.required' => 'O campo data é obrigatório para todos os bloqueios',
            'blocks.*.date.date' => 'O campo data deve ser uma data válida para todos os bloqueios',
            'blocks.*.time_start.required_if' => 'O campo hora de início é obrigatório quando o período é personalizado',
            'blocks.*.time_end.required_if' => 'O campo hora de término é obrigatório quando o período é personalizado',
        ];
	}

	protected function failedValidation(Validator $validator)
	{
		throw new HttpResponseException(response()->json(['message' => 'Erro de validação de atributo','error' => $validator->errors()], Response::HTTP_NOT_ACCEPTABLE));
	}
}
