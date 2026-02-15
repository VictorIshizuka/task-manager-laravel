<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskFileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $task = $this->route('task');

        return $this->user()->can('update', $task);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'file' => [
                'nullable',
                'file',
                'max:10240', // 10MB
                'mimes:pdf,jpg,jpeg,png',
            ],
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes(): array
    {
        return [
            'file' => 'arquivo',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'file.file' => 'O arquivo enviado não é válido.',
            'file.max' => 'O arquivo não pode ter mais de 10MB.',
            'file.mimes' => 'O arquivo deve ser: PDF, JPG, JPEG ou PNG.',
        ];
    }
}
