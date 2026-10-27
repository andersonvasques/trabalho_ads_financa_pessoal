<?php

namespace Api\Models\Lancamento\Resources;

use Api\Models\Lancamento\Lancamento;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Lancamento
 */
class LancamentoResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id_lancamento'             => $this->id_lancamento,
            'tipo_lancamento'           => $this->tipo_lancamento,
            'descricao_lancamento'      => $this->descricao_lancamento,
            'valor_lancamento'          => (float) $this->valor_lancamento,
            'data_lancamento'           => $this->data_lancamento?->format('Y-m-d'),
            'data_pagamento_lancamento' => $this->data_pagamento_lancamento?->format('Y-m-d'),
            'pago'                      => $this->isPago(),
            'observacao_lancamento'     => $this->observacao_lancamento,
            'conta'                     => [
                'id_conta'   => $this->id_conta,
                'nome_conta' => $this->conta?->nome_conta,
            ],
            'categoria'                 => [
                'id_categoria'   => $this->id_categoria,
                'nome_categoria' => $this->categoria?->nome_categoria,
            ],
        ];
    }
}
