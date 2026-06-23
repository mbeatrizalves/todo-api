<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTarefaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titulo'       => ['sometimes', 'required', 'string', 'max:150'],
            'descricao'    => ['nullable', 'string'],
            'status'       => ['nullable', Rule::in(['pendente', 'em_andamento', 'concluida'])],
            'prazo'        => ['nullable', 'date'],
            // Se categoria_id for enviado, deve pertencer ao usuário
            'categoria_id' => [
                'sometimes',
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
            'categoria_id.required' => 'A categoria é obrigatória.',
            'categoria_id.exists'   => 'A categoria informada não existe ou não pertence a você.',
        ];
    }
}
