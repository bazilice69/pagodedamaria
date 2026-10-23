<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoBeneficio extends Model
{
    protected $table = 'tipos_beneficio';

    protected $guarded = [];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    /** Sugestoes padrao, criadas no seed (os donos podem editar/adicionar). */
    public const SUGESTOES = [
        'Bolsa Família',
        'BPC / LOAS',
        'Auxílio Brasil',
        'Benefício de Prestação Continuada (idoso)',
        'Auxílio-Doença / INSS',
        'Tarifa Social de Energia',
        'Outro benefício',
    ];

    public function beneficios(): HasMany
    {
        return $this->hasMany(BeneficioPessoa::class, 'tipo_beneficio_id');
    }
}
