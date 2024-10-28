<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class FeedbacksRequest extends FormRequest
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
            'user_id' => 'required',
            'category_id' => 'required',
            'title' => 'required|string',
            'description' => 'required|string',
        ];
    }

    public function update()
    {
        return [
            'user_id' => 'required',
            'category_id' => 'required',
            'title' => 'required|string',
            'description' => 'required|string',
        ];
    }

    public function view()
    {
        return [];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'O campo usuário é obrigatório.',
            'category_id.required' => 'O campo categoria é obrigatório.',
            'title.required' => 'O campo título é obrigatório.',
            'title.string' => 'O título deve ser um texto.',
            'description.required' => 'O campo descrição é obrigatório.',
            'description.string' => 'A descrição deve ser um texto.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Erro de validação de atributo',
            'error' => $validator->errors()
        ], Response::HTTP_NOT_ACCEPTABLE));
    }
}
