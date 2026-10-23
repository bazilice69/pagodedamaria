<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Voluntario extends Model
{
    protected $table = 'voluntarios';

    protected $guarded = [];

    protected $casts = [
        'areas' => 'array',
        'ativo' => 'boolean',
        'aprovado_em' => 'datetime',
    ];

    public const AREAS = [
        'arrecadacao' => 'Arrecadação',
        'transporte' => 'Transporte',
        'organizacao' => 'Organização',
        'distribuicao' => 'Distribuição',
        'eventos' => 'Eventos',
        'comunicacao' => 'Comunicação',
        'outros' => 'Outros',
    ];

    public const STATUS_PENDENTE = 'pendente';
    public const STATUS_APROVADO = 'aprovado';
    public const STATUS_RECUSADO = 'recusado';

    public function arrecadacoes(): HasMany
    {
        return $this->hasMany(Arrecadacao::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function aprovador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'aprovado_por');
    }

    public function scopePendentes($query)
    {
        return $query->where('status', self::STATUS_PENDENTE);
    }

    public function estaPendente(): bool
    {
        return $this->status === self::STATUS_PENDENTE;
    }
}
