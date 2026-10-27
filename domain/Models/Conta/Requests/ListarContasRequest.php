<?php

namespace Api\Models\Conta\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListarContasRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'cad_ativo' => ['nullable', 'boolean'],
        ];
    }
}
