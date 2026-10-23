<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Campanha extends Model
{
    protected $guarded = [];

    protected $casts = [
        'meta' => 'decimal:2',
        'arrecadado' => 'decimal:2',
        'data_inicio' => 'date',
        'data_fim' => 'date',
        'ativa' => 'boolean',
        'publica' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (Campanha $campanha) {
            if (blank($campanha->slug) && filled($campanha->titulo)) {
                $campanha->slug = static::gerarSlug($campanha->titulo, $campanha->id);
            }
        });
    }

    protected static function gerarSlug(string $titulo, ?int $ignorarId = null): string
    {
        $base = Str::slug($titulo);
        $slug = $base;
        $i = 1;

        while (static::where('slug', $slug)
            ->when($ignorarId, fn ($q) => $q->where('id', '!=', $ignorarId))
            ->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    public function doacoes(): HasMany
    {
        return $this->hasMany(Doacao::class);
    }

    /** Percentual arrecadado (0 a 100). */
    public function getProgressoAttribute(): int
    {
        if (! $this->meta || $this->meta <= 0) {
            return 0;
        }

        return (int) min(100, round(($this->arrecadado / $this->meta) * 100));
    }

    public function getRestanteAttribute(): float
    {
        return max(0, (float) $this->meta - (float) $this->arrecadado);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
