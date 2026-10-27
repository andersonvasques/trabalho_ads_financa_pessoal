<?php

namespace Api\Models\Conta\Actions;

use Api\Models\Conta\Conta;
use Api\Models\User\User;
use Illuminate\Database\Eloquent\Collection;

final class ListarContasAction
{
    /**
     * Lista as contas do usuário.
     *
     * @param User $user
     * @param array $filtros cad_ativo
     * @return Collection<int, Conta>
     */
    public function handle(User $user, array $filtros = []): Collection
    {
        return $user->contas()
            ->when(isset($filtros['cad_ativo']), fn ($q) => $q->where('cad_ativo', (bool) $filtros['cad_ativo']))
            ->orderBy('nome_conta')
            ->get();
    }
}
