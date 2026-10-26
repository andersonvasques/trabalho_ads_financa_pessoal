<?php

namespace Api\Models\Lancamento;

use Api\Models\Categoria\Categoria;
use Api\Models\Conta\Conta;
use Api\Models\Enums\TipoMovimento;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lancamento extends Model
{
    protected $table      = 'mov_lancamento';
    protected $primaryKey = 'id_lancamento';

    const CREATED_AT = 'criado_em_lancamento';
    const UPDATED_AT = 'atualizado_em_lancamento';

    protected $fillable = [
        'id_conta',
        'id_categoria',
        'tipo_lancamento',
        'descricao_lancamento',
        'valor_lancamento',
        'data_lancamento',
        'data_pagamento_lancamento',
        'observacao_lancamento',
    ];

    protected function casts(): array
    {
        return [
            'tipo_lancamento'           => TipoMovimento::class,
            'valor_lancamento'          => 'decimal:2',
            'data_lancamento'           => 'date',
            'data_pagamento_lancamento' => 'date',
        ];
    }

    public function conta(): BelongsTo
    {
        // withTrashed: o lançamento continua mostrando a conta mesmo após soft delete
        return $this->belongsTo(Conta::class, 'id_conta', 'id_conta')->withTrashed();
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id_categoria')->withTrashed();
    }

    public function isPago(): bool
    {
        return $this->data_pagamento_lancamento !== null;
    }

    public function scopePagos(Builder $query): Builder
    {
        return $query->whereNotNull('data_pagamento_lancamento');
    }

    public function scopePendentes(Builder $query): Builder
    {
        return $query->whereNull('data_pagamento_lancamento');
    }

    public function scopeDoMes(Builder $query, int $ano, int $mes): Builder
    {
        return $query->whereYear('data_lancamento', $ano)->whereMonth('data_lancamento', $mes);
    }
}
