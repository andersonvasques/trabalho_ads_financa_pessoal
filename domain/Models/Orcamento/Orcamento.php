<?php

namespace Api\Models\Orcamento;

use Api\Models\Categoria\Categoria;
use Api\Models\Enums\TipoMovimento;
use Api\Models\Lancamento\Lancamento;
use Api\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Orcamento extends Model
{
    protected $table      = 'pla_orcamento';
    protected $primaryKey = 'id_orcamento';

    const CREATED_AT = 'criado_em_orcamento';
    const UPDATED_AT = 'atualizado_em_orcamento';

    protected $fillable = [
        'id_usuario',
        'id_categoria',
        'ano_orcamento',
        'mes_orcamento',
        'valor_limite_orcamento',
    ];

    protected function casts(): array
    {
        return [
            'ano_orcamento'          => 'integer',
            'mes_orcamento'          => 'integer',
            'valor_limite_orcamento' => 'decimal:2',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id_categoria')->withTrashed();
    }

    /**
     * Total gasto (despesas, pagas ou não) na categoria, no mês do orçamento,
     * somando apenas as contas do dono do orçamento.
     */
    public function valorGasto(): string
    {
        return (string) Lancamento::query()
            ->where('id_categoria', $this->id_categoria)
            ->where('tipo_lancamento', TipoMovimento::Despesa)
            ->doMes($this->ano_orcamento, $this->mes_orcamento)
            ->whereHas('conta', fn ($q) => $q->where('id_usuario', $this->id_usuario))
            ->sum('valor_lancamento');
    }
}
