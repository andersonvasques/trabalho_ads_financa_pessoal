<?php

namespace Api\Models\Conta\Actions;

use Api\Models\Conta\Conta;

final class ExcluirContaAction
{
    /**
     * Exclui (soft delete) uma conta do usuário.
     *
     * @param Conta $conta
     * @return void
     */
    public function handle(Conta $conta): void
    {
        $conta->delete();
    }
}
