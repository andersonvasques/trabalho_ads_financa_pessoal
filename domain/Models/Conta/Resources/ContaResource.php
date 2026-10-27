<?php

namespace Api\Models\Conta\Resources;

use Api\Models\Conta\Conta;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Conta
 */
class ContaResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id_conta'            => $this->id_conta,
            'nome_conta'          => $this->nome_conta,
            'tipo_conta'          => $this->tipo_conta,
            'instituicao_conta'   => $this->instituicao_conta,
            'saldo_inicial_conta' => (float) $this->saldo_inicial_conta,
            'saldo_atual_conta'   => $this->saldoAtual(),
            'cad_ativo'           => $this->cad_ativo,
        ];
    }
}
