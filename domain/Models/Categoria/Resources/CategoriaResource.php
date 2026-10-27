<?php

namespace Api\Models\Categoria\Resources;

use Api\Models\Categoria\Categoria;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Categoria
 */
class CategoriaResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id_categoria'    => $this->id_categoria,
            'nome_categoria'  => $this->nome_categoria,
            'tipo_categoria'  => $this->tipo_categoria,
            'icone_categoria' => $this->icone_categoria,
            'cor_categoria'   => $this->cor_categoria,
            'padrao'          => $this->isPadrao(),
        ];
    }
}
