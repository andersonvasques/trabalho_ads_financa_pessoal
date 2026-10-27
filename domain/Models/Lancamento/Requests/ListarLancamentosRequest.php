<?php

namespace Api\Models\Lancamento\Requests;

use Api\Models\Enums\TipoMovimento;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListarLancamentosRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'ano'             => ['nullable', 'integer', 'min:2000', 'max:2100', 'required_with:mes'],
            'mes'             => ['nullable', 'integer', 'min:1', 'max:12', 'required_with:ano'],
            'id_conta'        => ['nullable', 'integer'],
            'id_categoria'    => ['nullable', 'integer'],
            'tipo_lancamento' => ['nullable', Rule::enum(TipoMovimento::class)],
            'situacao'        => ['nullable', Rule::in(['pago', 'pendente'])],
            'por_pagina'      => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
