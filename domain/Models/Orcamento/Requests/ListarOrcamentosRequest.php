<?php

namespace Api\Models\Orcamento\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListarOrcamentosRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'ano' => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'mes' => ['nullable', 'integer', 'min:1', 'max:12'],
        ];
    }
}
