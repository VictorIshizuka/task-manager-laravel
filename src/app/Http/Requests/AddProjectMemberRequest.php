<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddProjectMemberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $project = $this->route('project');

        return $this->user()->can('update', $project);
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $project = $this->route('project');

        return [
            'user_id' => [
                'required',
                'exists:users,id',
                Rule::unique('project_user', 'user_id')
                    ->where(fn($q) => $q->where('project_id', $project->id))
            ],
            [
                'user_id.unique' => 'Este usuário já é membro do projeto.',
                'user_id.required' => 'Selecione um usuário.',
            ]
        ];
    }
}
