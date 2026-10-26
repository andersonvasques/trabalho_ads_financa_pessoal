<?php

namespace Api\Models\User;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Api\Models\Categoria\Categoria;
use Api\Models\Conta\Conta;
use Api\Models\Lancamento\Lancamento;
use Api\Models\Orcamento\Orcamento;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    /**
     * O model fica fora de App\Models, então o Laravel não acha a factory sozinho.
     */
    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function contas(): HasMany
    {
        return $this->hasMany(Conta::class, 'id_usuario', 'id');
    }

    /**
     * Apenas as categorias criadas pelo usuário (sem as padrão).
     * Para padrão + do usuário use Categoria::disponiveisPara($user->id).
     */
    public function categorias(): HasMany
    {
        return $this->hasMany(Categoria::class, 'id_usuario', 'id');
    }

    public function orcamentos(): HasMany
    {
        return $this->hasMany(Orcamento::class, 'id_usuario', 'id');
    }

    /**
     * Lançamentos de todas as contas do usuário.
     */
    public function lancamentos(): HasManyThrough
    {
        return $this->hasManyThrough(Lancamento::class, Conta::class, 'id_usuario', 'id_conta', 'id', 'id_conta');
    }
}
