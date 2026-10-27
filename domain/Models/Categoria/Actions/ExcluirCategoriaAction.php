<?php

namespace Api\Models\Categoria\Actions;

use Api\Models\Categoria\Categoria;

final class ExcluirCategoriaAction
{
    /**
     * Exclui (soft delete) uma categoria do usuário.
     * Os lançamentos antigos continuam apontando para ela.
     *
     * @param Categoria $categoria
     * @return void
     */
    public function handle(Categoria $categoria): void
    {
        $categoria->delete();
    }
}
