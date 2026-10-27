<?php

namespace Api\Models\Orcamento\Actions;

use Api\Models\Orcamento\Orcamento;
use Api\Models\User\User;

final class CriarOrcamentoAction
{
    /**
     * Cria um orçamento mensal para uma categoria de despesa.
     *
     * @param User $user
     * @param array $dados
     * @return Orcamento
     */
    public function handle(User $user, array $dados): Orcamento
    {
        return $user->orcamentos()->create($dados);
    }
}
