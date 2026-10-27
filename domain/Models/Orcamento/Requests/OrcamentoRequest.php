<?php

namespace Api\Models\Orcamento\Requests;

use Api\Models\Enums\TipoMovimento;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrcamentoRequest extends FormRequest
{
    public function rules(): array
    {
        $idUsuario = $this->user()?->id;

        return [
            'id_categoria'           => [
                'required',
                'integer',
                // Só categorias de despesa, padrão ou do usuário
                Rule::exists('cad_categoria', 'id_categoria')
                    ->where(fn (Builder $q) => $q->whereNull('id_usuario')->orWhere('id_usuario', $idUsuario))
                    ->where('tipo_categoria', TipoMovimento::Despesa->value)
                    ->whereNull('excluido_em_categoria'),
                // Um orçamento por categoria por mês
                Rule::unique('pla_orcamento', 'id_categoria')
                    ->where('id_usuario', $idUsuario)
                    ->where('ano_orcamento', $this->input('ano_orcamento'))
                    ->where('mes_orcamento', $this->input('mes_orcamento'))
                    ->ignore($this->route('id'), 'id_orcamento'),
            ],
            'ano_orcamento'          => ['required', 'integer', 'min:2000', 'max:2100'],
            'mes_orcamento'          => ['required', 'integer', 'min:1', 'max:12'],
            'valor_limite_orcamento' => ['required', 'numeric', 'gt:0', 'decimal:0,2'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_categoria.exists' => 'A categoria deve ser de despesa.',
            'id_categoria.unique' => 'Já existe um orçamento para esta categoria neste mês.',
        ];
    }
}
