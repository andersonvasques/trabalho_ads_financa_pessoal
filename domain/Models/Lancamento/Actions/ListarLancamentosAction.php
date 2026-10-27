<?php

namespace Api\Models\Lancamento\Actions;

use Api\Models\Lancamento\Lancamento;
use Api\Models\User\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class ListarLancamentosAction
{
    /**
     * Lista os lançamentos do usuário, com filtros opcionais e paginação.
     *
     * @param User $user
     * @param array $filtros ano, mes, id_conta, id_categoria, tipo_lancamento, situacao, por_pagina
     * @return LengthAwarePaginator<Lancamento>
     */
    public function handle(User $user, array $filtros = []): LengthAwarePaginator
    {
        return $user->lancamentos()
            ->with(['conta', 'categoria'])
            ->when(
                isset($filtros['ano'], $filtros['mes']),
                fn ($q) => $q->doMes((int) $filtros['ano'], (int) $filtros['mes'])
            )
            ->when($filtros['id_conta'] ?? null, fn ($q, $id) => $q->where('mov_lancamento.id_conta', $id))
            ->when($filtros['id_categoria'] ?? null, fn ($q, $id) => $q->where('id_categoria', $id))
            ->when($filtros['tipo_lancamento'] ?? null, fn ($q, $tipo) => $q->where('tipo_lancamento', $tipo))
            ->when(($filtros['situacao'] ?? null) === 'pago', fn ($q) => $q->pagos())
            ->when(($filtros['situacao'] ?? null) === 'pendente', fn ($q) => $q->pendentes())
            ->orderByDesc('data_lancamento')
            ->orderByDesc('id_lancamento')
            ->paginate($filtros['por_pagina'] ?? 20);
    }
}
