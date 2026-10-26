<?php

namespace Api\Models\Conta;

use Api\Models\Enums\TipoConta;
use Api\Models\Enums\TipoMovimento;
use Api\Models\Lancamento\Lancamento;
use Api\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conta extends Model
{
    use SoftDeletes;

    protected $table      = 'cad_conta';
    protected $primaryKey = 'id_conta';

    const CREATED_AT = 'criado_em_conta';
    const UPDATED_AT = 'atualizado_em_conta';
    const DELETED_AT = 'excluido_em_conta';

    protected $fillable = [
        'id_usuario',
        'nome_conta',
        'tipo_conta',
        'instituicao_conta',
        'saldo_inicial_conta',
        'cad_ativo',
    ];

    protected function casts(): array
    {
        return [
            'tipo_conta'          => TipoConta::class,
            'saldo_inicial_conta' => 'decimal:2',
            'cad_ativo'           => 'boolean',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }

    public function lancamentos(): HasMany
    {
        return $this->hasMany(Lancamento::class, 'id_conta', 'id_conta');
    }

    /**
     * Saldo atual = saldo inicial + receitas pagas - despesas pagas.
     * A soma é feita no banco, em decimal, numa única consulta.
     */
    public function saldoAtual(): float
    {
        $movimento = $this->lancamentos()
            ->whereNotNull('data_pagamento_lancamento')
            ->selectRaw(
                "COALESCE(SUM(CASE WHEN tipo_lancamento = ? THEN valor_lancamento ELSE -valor_lancamento END), 0) AS total",
                [TipoMovimento::Receita->value]
            )
            ->value('total');

        return round((float) $this->saldo_inicial_conta + (float) $movimento, 2);
    }
}
