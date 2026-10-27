<?php

namespace Api\Models\Orcamento\Actions;

use Api\Models\Orcamento\Orcamento;

final class AtualizarOrcamentoAction
{
    /**
     * Atualiza um orçamento do usuário.
     *
     * @param Orcamento $orcamento
     * @param array $dados
     * @return Orcamento
     */
    public function handle(Orcamento $orcamento, array $dados): Orcamento
    {
        $orcamento->update($dados);

        return $orcamento->load('categoria');
    }
}
