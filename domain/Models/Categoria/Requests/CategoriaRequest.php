<?php

namespace Api\Models\Categoria\Requests;

use Api\Models\Enums\TipoMovimento;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoriaRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nome_categoria'  => [
                'required',
                'string',
                'max:60',
                Rule::unique('cad_categoria', 'nome_categoria')
                    ->where('id_usuario', $this->user()?->id)
                    ->where('tipo_categoria', $this->input('tipo_categoria'))
                    ->whereNull('excluido_em_categoria')
                    ->ignore($this->route('id'), 'id_categoria'),
            ],
            'tipo_categoria'  => ['required', Rule::enum(TipoMovimento::class)],
            'icone_categoria' => ['nullable', 'string', 'max:50'],
            'cor_categoria'   => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ];
    }
}
