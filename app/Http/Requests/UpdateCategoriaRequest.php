<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoriaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => [
                'sometimes',
                'required',
                'string',
                'max:100',
                // Ignora o próprio registro no unique (pelo id da categoria sendo editada)
                Rule::unique('categorias')
                    ->where('user_id', $this->user()->id)
                    ->ignore($this->route('categoria')),
            ],
            'descricao' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O campo nome é obrigatório.',
            'nome.max'      => 'O nome não pode ultrapassar 100 caracteres.',
            'nome.unique'   => 'Você já possui uma categoria com esse nome.',
            'descricao.max' => 'A descrição não pode ultrapassar 255 caracteres.',
        ];
    }
}
