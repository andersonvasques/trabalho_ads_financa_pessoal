<?php

namespace Api\Models\Conta\Requests;

use Api\Models\Enums\TipoConta;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContaRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nome_conta'          => ['required', 'string', 'max:60'],
            'tipo_conta'          => ['required', Rule::enum(TipoConta::class)],
            'instituicao_conta'   => ['nullable', 'string', 'max:60'],
            'saldo_inicial_conta' => ['nullable', 'numeric', 'decimal:0,2'],
            'cad_ativo'           => ['sometimes', 'boolean'],
        ];
    }

    /**
     * saldo_inicial_conta vazio vira 0.
     */
    public function validated($key = null, $default = null)
    {
        $dados = parent::validated($key, $default);

        if ($key === null) {
            $dados['saldo_inicial_conta'] ??= 0;
        }

        return $dados;
    }
}
