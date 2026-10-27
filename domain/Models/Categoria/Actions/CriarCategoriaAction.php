<?php

namespace Api\Models\Categoria\Actions;

use Api\Models\Categoria\Categoria;
use Api\Models\User\User;

final class CriarCategoriaAction
{
    /**
     * Cria uma categoria para o usuário.
     *
     * @param User $user
     * @param array $dados
     * @return Categoria
     */
    public function handle(User $user, array $dados): Categoria
    {
        return $user->categorias()->create($dados);
    }
}
