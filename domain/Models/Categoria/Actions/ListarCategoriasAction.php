<?php

namespace Api\Models\Categoria\Actions;

use Api\Models\Categoria\Categoria;
use Api\Models\User\User;
use Illuminate\Database\Eloquent\Collection;

final class ListarCategoriasAction
{
    /**
     * Lista as categorias padrão do sistema + as criadas pelo usuário.
     *
     * @param User $user
     * @param array $filtros tipo_categoria
     * @return Collection<int, Categoria>
     */
    public function handle(User $user, array $filtros = []): Collection
    {
        return Categoria::disponiveisPara($user->id)
            ->when($filtros['tipo_categoria'] ?? null, fn ($q, $tipo) => $q->where('tipo_categoria', $tipo))
            ->orderBy('tipo_categoria')
            ->orderBy('nome_categoria')
            ->get();
    }
}
