<?php

namespace Api\Models\Conta;

use Api\Models\Conta\Actions\AtualizarContaAction;
use Api\Models\Conta\Actions\CriarContaAction;
use Api\Models\Conta\Actions\ExcluirContaAction;
use Api\Models\Conta\Actions\ListarContasAction;
use Api\Models\Conta\Requests\ContaRequest;
use Api\Models\Conta\Requests\ListarContasRequest;
use Api\Models\Conta\Resources\ContaResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ContaController extends Controller
{
    /**
     * Listar contas
     *
     * O saldo atual considera apenas lançamentos pagos.
     */
    public function index(ListarContasRequest $request, ListarContasAction $action): AnonymousResourceCollection
    {
        $rows = $action->handle($request->user(), $request->validated());

        return ContaResource::collection($rows);
    }

    /**
     * Criar conta
     */
    public function store(ContaRequest $request, CriarContaAction $action): JsonResponse
    {
        $row = $action->handle($request->user(), $request->validated());

        return (new ContaResource($row->refresh()))->response()->setStatusCode(201);
    }

    /**
     * Exibir conta
     */
    public function show(Request $request, int $id): ContaResource
    {
        return new ContaResource($this->buscar($request, $id));
    }

    /**
     * Atualizar conta
     */
    public function update(ContaRequest $request, int $id, AtualizarContaAction $action): ContaResource
    {
        $row = $action->handle($this->buscar($request, $id), $request->validated());

        return new ContaResource($row);
    }

    /**
     * Excluir conta
     */
    public function destroy(Request $request, int $id, ExcluirContaAction $action): Response
    {
        $action->handle($this->buscar($request, $id));

        return response()->noContent();
    }

    private function buscar(Request $request, int $id): Conta
    {
        return $request->user()->contas()->findOrFail($id);
    }
}
