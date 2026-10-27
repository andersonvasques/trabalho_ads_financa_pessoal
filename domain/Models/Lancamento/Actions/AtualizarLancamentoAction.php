<?php

namespace Api\Models\Lancamento\Actions;

use Api\Models\Categoria\Categoria;
use Api\Models\Lancamento\Lancamento;

final class AtualizarLancamentoAction
{
    /**
     * Atualiza um lançamento. O tipo acompanha a categoria escolhida.
     *
     * @param Lancamento $lancamento
     * @param array $dados
     * @return Lancamento
     */
    public function handle(Lancamento $lancamento, array $dados): Lancamento
    {
        $dados['tipo_lancamento'] = Categoria::findOrFail($dados['id_categoria'])->tipo_categoria;

        $lancamento->update($dados);

        return $lancamento->load(['conta', 'categoria']);
    }
}
