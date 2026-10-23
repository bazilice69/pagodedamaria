<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModeloTermo extends Model
{
    protected $table = 'modelos_termo';

    protected $guarded = [];

    protected $casts = ['ativo' => 'boolean'];

    public const TIPOS = [
        'passeio' => 'Autorização de passeio',
        'imagem' => 'Autorização de uso de imagem',
        'geral' => 'Termo geral',
    ];

    /** Placeholders disponiveis para os modelos. */
    public const PLACEHOLDERS = [
        '{{nome}}' => 'Nome da pessoa',
        '{{responsavel}}' => 'Nome do responsável',
        '{{codigo}}' => 'ID/matrícula',
        '{{nascimento}}' => 'Data de nascimento',
        '{{cpf}}' => 'CPF',
        '{{endereco}}' => 'Endereço completo',
        '{{associacao}}' => 'Nome da associação',
        '{{data}}' => 'Data de hoje',
    ];
}
