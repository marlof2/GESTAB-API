<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class GoogleCalendarEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'client_email' => 'required|email'
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'O título é obrigatório',
            'title.max' => 'O título não pode ter mais que 255 caracteres',
            'description.required' => 'A descrição é obrigatória',
            'start_time.required' => 'A data de início é obrigatória',
            'start_time.date' => 'A data de início deve ser uma data válida',
            'end_time.required' => 'A data de término é obrigatória',
            'end_time.date' => 'A data de término deve ser uma data válida',
            'end_time.after' => 'A data de término deve ser posterior à data de início',
            'client_email.required' => 'O email do cliente é obrigatório',
            'client_email.email' => 'O email do cliente deve ser um email válido'
        ];
    }
}
