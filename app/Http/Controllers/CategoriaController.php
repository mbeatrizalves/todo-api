<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoriaRequest;
use App\Http\Requests\UpdateCategoriaRequest;
use App\Models\Categoria;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    use ApiResponse;

    // Lista apenas as categorias do usuário autenticado
    public function index(Request $request): JsonResponse
    {
        $categorias = $request->user()->categorias()->withCount('tarefas')->get();

        return $this->successResponse('Categorias listadas com sucesso.', $categorias);
    }

    public function store(StoreCategoriaRequest $request): JsonResponse
    {
        $categoria = $request->user()->categorias()->create($request->validated());

        return $this->successResponse('Categoria criada com sucesso.', $categoria, 201);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $categoria = $request->user()->categorias()->with('tarefas')->find($id);

        if (! $categoria) {
            return $this->errorResponse('Categoria não encontrada.', null, 404);
        }

        return $this->successResponse('Categoria encontrada.', $categoria);
    }

    public function update(UpdateCategoriaRequest $request, int $id): JsonResponse
    {
        $categoria = $request->user()->categorias()->find($id);

        if (! $categoria) {
            return $this->errorResponse('Categoria não encontrada.', null, 404);
        }

        $categoria->update($request->validated());

        return $this->successResponse('Categoria atualizada com sucesso.', $categoria);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $categoria = $request->user()->categorias()->find($id);

        if (! $categoria) {
            return $this->errorResponse('Categoria não encontrada.', null, 404);
        }

        // Decisão: impede exclusão se houver tarefas vinculadas (ver README para justificativa)
        if ($categoria->tarefas()->exists()) {
            return $this->errorResponse(
                'Não é possível excluir a categoria pois existem tarefas vinculadas a ela. Remova as tarefas primeiro.',
                null,
                422
            );
        }

        $categoria->delete();

        return $this->successResponse('Categoria excluída com sucesso.');
    }
}
