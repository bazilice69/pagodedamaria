<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Curso extends Model
{
    protected $guarded = [];

    protected $casts = [
        'ativo' => 'boolean',
        'vagas' => 'integer',
        'idade_min' => 'integer',
        'idade_max' => 'integer',
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(CategoriaCurso::class, 'categoria_curso_id');
    }

    public function professor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'professor_id');
    }

    public function matriculas(): HasMany
    {
        return $this->hasMany(Matricula::class);
    }

    public function alunos(): BelongsToMany
    {
        return $this->belongsToMany(Pessoa::class, 'matriculas')
            ->withPivot(['id', 'status', 'data_matricula'])
            ->withTimestamps();
    }

    public function presencas(): HasMany
    {
        return $this->hasMany(Presenca::class);
    }

    public function getVagasRestantesAttribute(): ?int
    {
        if (! $this->vagas) {
            return null;
        }

        return max(0, $this->vagas - $this->matriculas()->where('status', 'ativa')->count());
    }
}
