<?php

namespace Api\Models\Conta\Actions;

use Api\Models\Conta\Conta;
use Api\Models\User\User;

final class CriarContaAction
{
    /**
     * Cria uma conta para o usuário.
     *
     * @param User $user
     * @param array $dados
     * @return Conta
     */
    public function handle(User $user, array $dados): Conta
    {
        return $user->contas()->create($dados);
    }
}
