<?php

namespace Api\Models\Categoria;

use Api\Models\Categoria\Actions\AtualizarCategoriaAction;
use Api\Models\Categoria\Actions\CriarCategoriaAction;
use Api\Models\Categoria\Actions\ExcluirCategoriaAction;
use Api\Models\Categoria\Actions\ListarCategoriasAction;
use Api\Models\Categoria\Requests\CategoriaRequest;
use Api\Models\Categoria\Requests\ListarCategoriasRequest;
use Api\Models\Categoria\Resources\CategoriaResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class CategoriaController extends Controller
{
    /**
     * Listar categorias
     *
     * Retorna as categorias padrão do sistema e as criadas pelo usuário.
     */
    public function index(ListarCategoriasRequest $request, ListarCategoriasAction $action): AnonymousResourceCollection
    {
        $rows = $action->handle($request->user(), $request->validated());

        return CategoriaResource::collection($rows);
    }

    /**
     * Criar categoria
     */
    public function store(CategoriaRequest $request, CriarCategoriaAction $action): JsonResponse
    {
        $row = $action->handle($request->user(), $request->validated());

        return (new CategoriaResource($row))->response()->setStatusCode(201);
    }

    /**
     * Exibir categoria
     */
    public function show(Request $request, int $id): CategoriaResource
    {
        $row = Categoria::disponiveisPara($request->user()->id)->findOrFail($id);

        return new CategoriaResource($row);
    }

    /**
     * Atualizar categoria
     *
     * Somente categorias criadas pelo usuário podem ser alteradas.
     */
    public function update(CategoriaRequest $request, int $id, AtualizarCategoriaAction $action): CategoriaResource
    {
        $row = $action->handle($this->buscarDoUsuario($request, $id), $request->validated());

        return new CategoriaResource($row);
    }

    /**
     * Excluir categoria
     *
     * Somente categorias criadas pelo usuário podem ser excluídas.
     */
    public function destroy(Request $request, int $id, ExcluirCategoriaAction $action): Response
    {
        $action->handle($this->buscarDoUsuario($request, $id));

        return response()->noContent();
    }

    private function buscarDoUsuario(Request $request, int $id): Categoria
    {
        $categoria = Categoria::disponiveisPara($request->user()->id)->findOrFail($id);

        abort_if($categoria->isPadrao(), 403, 'Categorias padrão do sistema não podem ser alteradas.');

        return $categoria;
    }
}
