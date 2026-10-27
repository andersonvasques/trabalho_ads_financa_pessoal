<?php

namespace Api\Models\Orcamento\Actions;

use Api\Models\Orcamento\Orcamento;

final class ExcluirOrcamentoAction
{
    /**
     * Exclui um orçamento do usuário.
     *
     * @param Orcamento $orcamento
     * @return void
     */
    public function handle(Orcamento $orcamento): void
    {
        $orcamento->delete();
    }
}
