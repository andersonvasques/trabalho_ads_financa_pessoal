<?php

namespace Api\Models\Conta\Actions;

use Api\Models\Conta\Conta;

final class AtualizarContaAction
{
    /**
     * Atualiza uma conta do usuário.
     *
     * @param Conta $conta
     * @param array $dados
     * @return Conta
     */
    public function handle(Conta $conta, array $dados): Conta
    {
        $conta->update($dados);

        return $conta;
    }
}
