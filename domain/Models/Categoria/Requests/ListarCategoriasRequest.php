<?php

namespace Api\Models\Categoria\Requests;

use Api\Models\Enums\TipoMovimento;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListarCategoriasRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'tipo_categoria' => ['nullable', Rule::enum(TipoMovimento::class)],
        ];
    }
}
