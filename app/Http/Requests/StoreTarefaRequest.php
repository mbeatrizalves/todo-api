<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTarefaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titulo'       => ['required', 'string', 'max:150'],
            'descricao'    => ['nullable', 'string'],
            'status'       => ['nullable', Rule::in(['pendente', 'em_andamento', 'concluida'])],
            'prazo'        => ['nullable', 'date', 'after_or_equal:today'],
            // Valida que a categoria pertence ao usuário autenticado
            'categoria_id' => [
                'required',
                'integer',
                Rule::exists('categorias', 'id')->where('user_id', $this->user()->id),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'titulo.required'       => 'O campo título é obrigatório.',
            'titulo.max'            => 'O título não pode ultrapassar 150 caracteres.',
            'status.in'             => 'O status deve ser: pendente, em_andamento ou concluida.',
            'prazo.date'            => 'O prazo deve ser uma data válida.',
            'prazo.after_or_equal'  => 'O prazo não pode ser uma data passada.',
            'categoria_id.required' => 'A categoria é obrigatória.',
            'categoria_id.exists'   => 'A categoria informada não existe ou não pertence a você.',
        ];
    }
}
