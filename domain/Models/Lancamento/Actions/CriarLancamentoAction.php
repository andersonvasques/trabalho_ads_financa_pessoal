<?php

namespace Api\Models\Lancamento\Actions;

use Api\Models\Categoria\Categoria;
use Api\Models\Lancamento\Lancamento;

final class CriarLancamentoAction
{
    /**
     * Cria um lançamento. O tipo (receita/despesa) vem da categoria escolhida.
     * A request já garante que a conta e a categoria pertencem ao usuário.
     *
     * @param array $dados
     * @return Lancamento
     */
    public function handle(array $dados): Lancamento
    {
        $dados['tipo_lancamento'] = Categoria::findOrFail($dados['id_categoria'])->tipo_categoria;

        return Lancamento::create($dados);
    }
}
