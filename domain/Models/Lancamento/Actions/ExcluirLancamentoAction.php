<?php

namespace Api\Models\Lancamento\Actions;

use Api\Models\Lancamento\Lancamento;

final class ExcluirLancamentoAction
{
    /**
     * Exclui um lançamento.
     *
     * @param Lancamento $lancamento
     * @return void
     */
    public function handle(Lancamento $lancamento): void
    {
        $lancamento->delete();
    }
}
