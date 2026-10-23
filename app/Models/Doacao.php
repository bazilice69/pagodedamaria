<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Doacao extends Model
{
    protected $table = 'doacoes';

    protected $guarded = [];

    protected $casts = [
        'anonimo' => 'boolean',
        'entrou_estoque' => 'boolean',
        'quantidade' => 'decimal:2',
        'valor' => 'decimal:2',
        'data' => 'date',
    ];

    public function campanha(): BelongsTo
    {
        return $this->belongsTo(Campanha::class);
    }

    public function arrecadacao(): BelongsTo
    {
        return $this->belongsTo(Arrecadacao::class);
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function recebedor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recebido_por');
    }

    /** Nome de exibicao do doador, respeitando anonimato. */
    public function getDoadorExibicaoAttribute(): string
    {
        if ($this->anonimo || blank($this->doador_nome)) {
            return 'Doador anônimo';
        }

        return $this->doador_nome;
    }
}
