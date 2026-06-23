<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTarefaRequest;
use App\Http\Requests\UpdateTarefaRequest;
use App\Models\Tarefa;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TarefaController extends Controller
{
    use ApiResponse;

    // Lista as tarefas do usuário autenticado; aceita filtros ?status= e ?categoria_id=
    public function index(Request $request): JsonResponse
    {
        $query = $request->user()->tarefas()->with('categoria');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        return $this->successResponse('Tarefas listadas com sucesso.', $query->get());
    }

    public function store(StoreTarefaRequest $request): JsonResponse
    {
        $data          = $request->validated();
        $data['user_id'] = $request->user()->id;

        $tarefa = Tarefa::create($data);
        $tarefa->load('categoria');

        return $this->successResponse('Tarefa criada com sucesso.', $tarefa, 201);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $tarefa = $request->user()->tarefas()->with('categoria')->find($id);

        if (! $tarefa) {
            return $this->errorResponse('Tarefa não encontrada.', null, 404);
        }

        return $this->successResponse('Tarefa encontrada.', $tarefa);
    }

    public function update(UpdateTarefaRequest $request, int $id): JsonResponse
    {
        $tarefa = $request->user()->tarefas()->find($id);

        if (! $tarefa) {
            return $this->errorResponse('Tarefa não encontrada.', null, 404);
        }

        $tarefa->update($request->validated());
        $tarefa->load('categoria');

        return $this->successResponse('Tarefa atualizada com sucesso.', $tarefa);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $tarefa = $request->user()->tarefas()->find($id);

        if (! $tarefa) {
            return $this->errorResponse('Tarefa não encontrada.', null, 404);
        }

        $tarefa->delete();

        return $this->successResponse('Tarefa excluída com sucesso.');
    }
}
