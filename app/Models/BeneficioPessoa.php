<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BeneficioPessoa extends Model
{
    protected $table = 'beneficios_pessoa';

    protected $guarded = [];

    protected $casts = [
        'valor' => 'decimal:2',
        'ativo' => 'boolean',
    ];

    public function pessoa(): BelongsTo
    {
        return $this->belongsTo(Pessoa::class);
    }

    public function tipo(): BelongsTo
    {
        return $this->belongsTo(TipoBeneficio::class, 'tipo_beneficio_id');
    }

    /** Nome do beneficio: tipo cadastrado ou texto livre. */
    public function getNomeAttribute(): string
    {
        return $this->tipo?->nome ?: ($this->tipo_livre ?: 'Benefício');
    }
}
