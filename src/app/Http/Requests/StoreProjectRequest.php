<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:start_date',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'title' => 'título',
            'description' => 'descrição',
            'start_date' => 'data de início',
            'due_date' => 'data de vencimento',
            'file' => 'arquivo'
        ];
    }

    /**
     * Get custom error messages for validation failures.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'O título é obrigatório.',
            'title.string' => 'O título deve ser uma string.',
            'title.max' => 'O título não pode exceder 255 caracteres.',
            'description.string' => 'A descrição deve ser uma string.',
            'start_date.required' => 'A data de início é obrigatória.',
            'start_date.date' => 'A data de início deve ser uma data válida.',
            'due_date.required' => 'A data de vencimento é obrigatória.',
            'due_date.date' => 'A data de conclusão deve ser uma data válida.',
            'due_date.after_or_equal' => 'A data de conclusão deve ser igual ou posterior à data de início.',
            'file.file' => 'O arquivo deve ser um arquivo válido.',
            'file.mimes' => 'O arquivo deve ser um arquivo do tipo: pdf, jpg, jpeg ou png.',
            'file.max' => 'O arquivo não pode exceder 10MB.',
        ];
    }
}
