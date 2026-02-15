<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
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
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high',
            'status' => 'nullable|in:pending,in_progress,done',
            'file' => 'nullable|mimes:pdf|max:10240|file', // 10MB

        ];
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes(): array
    {
        return [
            'title' => 'título',
            'description' => 'descrição',
            'due_date' => 'data de vencimento',
            'priority' => 'prioridade',
            'status' => 'status',
            'file' => 'arquivo',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'O título é obrigatório.',
            'title.max' => 'O título não pode ter mais de 255 caracteres.',
            'due_date.date' => 'A data de vencimento deve ser uma data válida.',
            'priority.required' => 'A prioridade é obrigatória.',
            'priority.in' => 'A prioridade deve ser: baixa, média ou alta.',
            'status.in' => 'O status deve ser: pendente, em andamento ou concluído.',
            'file.mimes' => 'O arquivo deve ser um PDF.',
            'file.max' => 'O arquivo não pode ter mais de 10MB.',
            'file.file' => 'O arquivo enviado não é válido.',
        ];
    }
}
