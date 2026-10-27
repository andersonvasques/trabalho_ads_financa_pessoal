<?php

namespace Api\Models\Lancamento\Requests;

use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LancamentoRequest extends FormRequest
{
    public function rules(): array
    {
        $idUsuario = $this->user()?->id;

        return [
            // Só contas do usuário que não foram excluídas
            'id_conta'                  => [
                'required',
                'integer',
                Rule::exists('cad_conta', 'id_conta')
                    ->where('id_usuario', $idUsuario)
                    ->whereNull('excluido_em_conta'),
            ],
            // Categorias padrão ou do usuário, não excluídas
            'id_categoria'              => [
                'required',
                'integer',
                Rule::exists('cad_categoria', 'id_categoria')
                    ->where(fn (Builder $q) => $q->whereNull('id_usuario')->orWhere('id_usuario', $idUsuario))
                    ->whereNull('excluido_em_categoria'),
            ],
            'descricao_lancamento'      => ['required', 'string', 'max:150'],
            'valor_lancamento'          => ['required', 'numeric', 'gt:0', 'decimal:0,2'],
            'data_lancamento'           => ['required', 'date_format:Y-m-d'],
            'data_pagamento_lancamento' => ['nullable', 'date_format:Y-m-d'],
            'observacao_lancamento'     => ['nullable', 'string', 'max:500'],
        ];
    }
}
