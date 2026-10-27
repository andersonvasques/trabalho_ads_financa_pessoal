<?php

namespace Api\Models\Orcamento;

use Api\Models\Orcamento\Actions\AtualizarOrcamentoAction;
use Api\Models\Orcamento\Actions\CriarOrcamentoAction;
use Api\Models\Orcamento\Actions\ExcluirOrcamentoAction;
use Api\Models\Orcamento\Actions\ListarOrcamentosAction;
use Api\Models\Orcamento\Requests\ListarOrcamentosRequest;
use Api\Models\Orcamento\Requests\OrcamentoRequest;
use Api\Models\Orcamento\Resources\OrcamentoResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class OrcamentoController extends Controller
{
    /**
     * Listar orçamentos
     *
     * Cada orçamento traz o valor já gasto no mês e quanto ainda resta.
     */
    public function index(ListarOrcamentosRequest $request, ListarOrcamentosAction $action): AnonymousResourceCollection
    {
        $rows = $action->handle($request->user(), $request->validated());

        return OrcamentoResource::collection($rows);
    }

    /**
     * Criar orçamento
     */
    public function store(OrcamentoRequest $request, CriarOrcamentoAction $action): JsonResponse
    {
        $row = $action->handle($request->user(), $request->validated());

        return (new OrcamentoResource($row->load('categoria')))->response()->setStatusCode(201);
    }

    /**
     * Exibir orçamento
     */
    public function show(Request $request, int $id): OrcamentoResource
    {
        return new OrcamentoResource($this->buscar($request, $id));
    }

    /**
     * Atualizar orçamento
     */
    public function update(OrcamentoRequest $request, int $id, AtualizarOrcamentoAction $action): OrcamentoResource
    {
        $row = $action->handle($this->buscar($request, $id), $request->validated());

        return new OrcamentoResource($row);
    }

    /**
     * Excluir orçamento
     */
    public function destroy(Request $request, int $id, ExcluirOrcamentoAction $action): Response
    {
        $action->handle($this->buscar($request, $id));

        return response()->noContent();
    }

    private function buscar(Request $request, int $id): Orcamento
    {
        return $request->user()->orcamentos()->with('categoria')->findOrFail($id);
    }
}
