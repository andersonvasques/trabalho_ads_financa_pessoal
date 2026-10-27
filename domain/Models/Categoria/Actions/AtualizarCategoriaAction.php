<?php

namespace Api\Models\Categoria\Actions;

use Api\Models\Categoria\Categoria;
use Illuminate\Validation\ValidationException;

final class AtualizarCategoriaAction
{
    /**
     * Atualiza uma categoria do usuário.
     *
     * @param Categoria $categoria
     * @param array $dados
     * @return Categoria
     */
    public function handle(Categoria $categoria, array $dados): Categoria
    {
        $mudouTipo = $dados['tipo_categoria'] !== $categoria->tipo_categoria->value;

        // O tipo do lançamento vem da categoria, então não pode mudar depois de usada.
        throw_if(
            condition: $mudouTipo && $categoria->lancamentos()->exists(),
            exception: ValidationException::withMessages([
                'tipo_categoria' => 'Não é possível alterar o tipo de uma categoria que já possui lançamentos.',
            ])
        );

        $categoria->update($dados);

        return $categoria;
    }
}
