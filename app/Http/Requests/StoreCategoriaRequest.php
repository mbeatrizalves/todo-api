<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoriaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Autorização real é feita no controller via middleware auth:sanctum
    }

    public function rules(): array
    {
        return [
            'nome' => [
                'required',
                'string',
                'max:100',
                // Unique composto: user_id + nome (um usuário não pode ter categorias com mesmo nome)
                Rule::unique('categorias')->where('user_id', $this->user()->id),
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
