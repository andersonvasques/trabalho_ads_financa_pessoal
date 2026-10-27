<?php

namespace Api\Models\Orcamento\Resources;

use Api\Models\Orcamento\Orcamento;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Orcamento
 */
class OrcamentoResource extends JsonResource
{
    public function toArray($request): array
    {
        $limite = (float) $this->valor_limite_orcamento;
        $gasto  = (float) $this->valorGasto();

        return [
            'id_orcamento'           => $this->id_orcamento,
            'ano_orcamento'          => $this->ano_orcamento,
            'mes_orcamento'          => $this->mes_orcamento,
            'valor_limite_orcamento' => $limite,
            'valor_gasto'            => $gasto,
            'valor_restante'         => round($limite - $gasto, 2),
            'percentual_utilizado'   => $limite > 0 ? round($gasto / $limite * 100, 2) : 0,
            'categoria'              => [
                'id_categoria'   => $this->id_categoria,
                'nome_categoria' => $this->categoria?->nome_categoria,
            ],
        ];
    }
}
