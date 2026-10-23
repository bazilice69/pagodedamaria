<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    protected $table = 'itens';

    protected $guarded = [];

    protected $casts = [
        'quantidade' => 'decimal:2',
        'quantidade_minima' => 'decimal:2',
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function movimentacoes(): HasMany
    {
        return $this->hasMany(Movimentacao::class);
    }

    /** Estoque abaixo (ou igual) do minimo definido. */
    public function getEstoqueBaixoAttribute(): bool
    {
        return $this->quantidade_minima > 0
            && $this->quantidade <= $this->quantidade_minima;
    }

    /** Status visual do estoque: baixo | atencao | normal. */
    public function getStatusEstoqueAttribute(): string
    {
        if ($this->quantidade_minima <= 0) {
            return 'normal';
        }

        if ($this->quantidade <= $this->quantidade_minima) {
            return 'baixo';
        }

        if ($this->quantidade <= $this->quantidade_minima * 1.25) {
            return 'atencao';
        }

        return 'normal';
    }
}
