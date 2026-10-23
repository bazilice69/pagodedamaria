<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Hierarquia real das associacoes (do maior para o menor acesso).
    public const ROLE_PRESIDENTE = 'presidente';
    public const ROLE_VICE = 'vice';
    public const ROLE_DIRETOR = 'diretor';
    public const ROLE_PROFESSOR = 'professor';
    public const ROLE_VOLUNTARIO = 'voluntario';

    // Compatibilidade com a V1 (o 'admin' do seed vira presidente na pratica).
    public const ROLE_ADMIN = 'admin';

    /** Papeis com poder total de gestao (o "circulo de confianca"). */
    public const ROLES_GESTAO = [
        self::ROLE_ADMIN,
        self::ROLE_PRESIDENTE,
        self::ROLE_VICE,
        self::ROLE_DIRETOR,
    ];

    public const ROLES_LABEL = [
        self::ROLE_PRESIDENTE => 'Presidente',
        self::ROLE_VICE => 'Vice-Presidente',
        self::ROLE_DIRETOR => 'Diretor(a)',
        self::ROLE_PROFESSOR => 'Professor(a)',
        self::ROLE_VOLUNTARIO => 'Voluntário(a)',
        self::ROLE_ADMIN => 'Administrador',
    ];

    public const STATUS_PENDENTE = 'pendente';
    public const STATUS_APROVADO = 'aprovado';
    public const STATUS_RECUSADO = 'recusado';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'cargo',
        'telefone',
        'ativo',
        'status',
        'aprovado_por',
        'aprovado_em',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'ativo' => 'boolean',
            'aprovado_em' => 'datetime',
        ];
    }

    public function aprovador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'aprovado_por');
    }

    /** Pessoa (ficha) ligada a esta conta, quando houver. */
    public function pessoa(): BelongsTo
    {
        return $this->belongsTo(Pessoa::class);
    }

    /** Tem poder total de gestao (presidente/vice/diretor/admin). */
    public function isGestor(): bool
    {
        return in_array($this->role, self::ROLES_GESTAO, true);
    }

    /** Mantido por compatibilidade com a V1. */
    public function isAdmin(): bool
    {
        return $this->isGestor();
    }

    /** Pode aprovar candidaturas de voluntarios. */
    public function podeAprovar(): bool
    {
        return $this->isGestor();
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function getCargoExibicaoAttribute(): string
    {
        return $this->cargo ?: (self::ROLES_LABEL[$this->role] ?? ucfirst($this->role));
    }

    public function estaAprovado(): bool
    {
        return $this->status === self::STATUS_APROVADO;
    }
}
