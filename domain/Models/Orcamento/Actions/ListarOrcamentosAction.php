<?php

namespace Api\Models\Orcamento\Actions;

use Api\Models\Orcamento\Orcamento;
use Api\Models\User\User;
use Illuminate\Database\Eloquent\Collection;

final class ListarOrcamentosAction
{
    /**
     * Lista os orçamentos do usuário.
     *
     * @param User $user
     * @param array $filtros ano, mes
     * @return Collection<int, Orcamento>
     */
    public function handle(User $user, array $filtros = []): Collection
    {
        return $user->orcamentos()
            ->with('categoria')
            ->when($filtros['ano'] ?? null, fn ($q, $ano) => $q->where('ano_orcamento', $ano))
            ->when($filtros['mes'] ?? null, fn ($q, $mes) => $q->where('mes_orcamento', $mes))
            ->orderByDesc('ano_orcamento')
            ->orderByDesc('mes_orcamento')
            ->get();
    }
}
