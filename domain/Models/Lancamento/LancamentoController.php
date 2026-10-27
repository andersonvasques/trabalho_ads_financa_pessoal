<?php

namespace Api\Models\Lancamento;

use Api\Models\Lancamento\Actions\AtualizarLancamentoAction;
use Api\Models\Lancamento\Actions\CriarLancamentoAction;
use Api\Models\Lancamento\Actions\ExcluirLancamentoAction;
use Api\Models\Lancamento\Actions\ListarLancamentosAction;
use Api\Models\Lancamento\Requests\LancamentoRequest;
use Api\Models\Lancamento\Requests\ListarLancamentosRequest;
use Api\Models\Lancamento\Resources\LancamentoResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class LancamentoController extends Controller
{
    /**
     * Listar lançamentos
     *
     * Filtros opcionais: ano + mes, id_conta, id_categoria, tipo_lancamento, situacao (pago/pendente).
     */
    public function index(ListarLancamentosRequest $request, ListarLancamentosAction $action): AnonymousResourceCollection
    {
        $rows = $action->handle($request->user(), $request->validated());

        return LancamentoResource::collection($rows);
    }

    /**
     * Criar lançamento
     *
     * O tipo (receita/despesa) é definido pela categoria. Sem data_pagamento_lancamento, fica pendente.
     */
    public function store(LancamentoRequest $request, CriarLancamentoAction $action): JsonResponse
    {
        $row = $action->handle($request->validated());

        return (new LancamentoResource($row->load(['conta', 'categoria'])))->response()->setStatusCode(201);
    }

    /**
     * Exibir lançamento
     */
    public function show(Request $request, int $id): LancamentoResource
    {
        return new LancamentoResource($this->buscar($request, $id));
    }

    /**
     * Atualizar lançamento
     */
    public function update(LancamentoRequest $request, int $id, AtualizarLancamentoAction $action): LancamentoResource
    {
        $row = $action->handle($this->buscar($request, $id), $request->validated());

        return new LancamentoResource($row);
    }

    /**
     * Excluir lançamento
     */
    public function destroy(Request $request, int $id, ExcluirLancamentoAction $action): Response
    {
        $action->handle($this->buscar($request, $id));

        return response()->noContent();
    }

    private function buscar(Request $request, int $id): Lancamento
    {
        return $request->user()->lancamentos()->with(['conta', 'categoria'])->findOrFail($id);
    }
}
