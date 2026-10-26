<?php

namespace Api\Models\Categoria;

use Api\Models\Enums\TipoMovimento;
use Api\Models\Lancamento\Lancamento;
use Api\Models\Orcamento\Orcamento;
use Api\Models\User\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categoria extends Model
{
    use SoftDeletes;

    protected $table      = 'cad_categoria';
    protected $primaryKey = 'id_categoria';

    const CREATED_AT = 'criado_em_categoria';
    const UPDATED_AT = 'atualizado_em_categoria';
    const DELETED_AT = 'excluido_em_categoria';

    protected $fillable = [
        'id_usuario',
        'nome_categoria',
        'tipo_categoria',
        'icone_categoria',
        'cor_categoria',
    ];

    protected function casts(): array
    {
        return [
            'tipo_categoria' => TipoMovimento::class,
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }

    public function lancamentos(): HasMany
    {
        return $this->hasMany(Lancamento::class, 'id_categoria', 'id_categoria');
    }

    public function orcamentos(): HasMany
    {
        return $this->hasMany(Orcamento::class, 'id_categoria', 'id_categoria');
    }

    public function isPadrao(): bool
    {
        return $this->id_usuario === null;
    }

    /**
     * Categorias visíveis para o usuário: as padrão do sistema + as dele.
     */
    public function scopeDisponiveisPara(Builder $query, int $idUsuario): Builder
    {
        return $query->where(fn (Builder $q) => $q
            ->whereNull('id_usuario')
            ->orWhere('id_usuario', $idUsuario));
    }
}
