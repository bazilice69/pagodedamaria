<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pessoa extends Model
{
    protected $table = 'pessoas';

    protected $guarded = [];

    protected $casts = [
        'nascimento' => 'date',
        'autoriza_imagem' => 'boolean',
        'autoriza_imagem_data' => 'date',
        'e_aluno' => 'boolean',
        'e_responsavel' => 'boolean',
        'e_voluntario' => 'boolean',
        'e_doador' => 'boolean',
        'e_beneficiario' => 'boolean',
        'ativo' => 'boolean',
    ];

    protected static function booted(): void
    {
        // Gera o codigo/matricula unica (PDM-000001) ao criar.
        static::created(function (Pessoa $pessoa) {
            if (blank($pessoa->codigo)) {
                $pessoa->codigo = 'PDM-'.str_pad((string) $pessoa->id, 6, '0', STR_PAD_LEFT);
                $pessoa->saveQuietly();
            }
        });
    }

    public function responsavel(): BelongsTo
    {
        return $this->belongsTo(Pessoa::class, 'responsavel_id');
    }

    public function dependentes(): HasMany
    {
        return $this->hasMany(Pessoa::class, 'responsavel_id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function matriculas(): HasMany
    {
        return $this->hasMany(Matricula::class);
    }

    public function beneficios(): HasMany
    {
        return $this->hasMany(BeneficioPessoa::class);
    }

    /** Cursos em que a pessoa esta matriculada (ativos). */
    public function cursos()
    {
        return $this->belongsToMany(Curso::class, 'matriculas')
            ->withPivot(['status', 'data_matricula'])
            ->withTimestamps();
    }

    public function getIdadeAttribute(): ?int
    {
        return $this->nascimento?->age;
    }

    public function getEnderecoCompletoAttribute(): string
    {
        $partes = array_filter([
            $this->logradouro,
            $this->numero,
            $this->complemento,
            $this->bairro,
            $this->cidade && $this->estado ? "{$this->cidade}/{$this->estado}" : ($this->cidade ?: null),
        ]);

        return implode(', ', $partes);
    }

    /** Lista de papeis legiveis, para exibicao. */
    public function getPapeisAttribute(): array
    {
        $mapa = [
            'e_aluno' => 'Aluno(a)',
            'e_responsavel' => 'Responsável',
            'e_voluntario' => 'Voluntário(a)',
            'e_doador' => 'Doador(a)',
            'e_beneficiario' => 'Beneficiário(a)',
        ];

        $papeis = [];
        foreach ($mapa as $campo => $label) {
            if ($this->{$campo}) {
                $papeis[] = $label;
            }
        }

        return $papeis;
    }

    public function scopeAlunos($query)
    {
        return $query->where('e_aluno', true);
    }

    public function scopeBusca($query, ?string $termo)
    {
        if (blank($termo)) {
            return $query;
        }

        return $query->where(function ($q) use ($termo) {
            $q->where('nome', 'like', "%{$termo}%")
                ->orWhere('codigo', 'like', "%{$termo}%")
                ->orWhere('cpf', 'like', "%{$termo}%")
                ->orWhere('telefone', 'like', "%{$termo}%");
        });
    }
}
