<?php

namespace Database\Seeders;

use App\Models\Arrecadacao;
use App\Models\Campanha;
use App\Models\Categoria;
use App\Models\Configuracao;
use App\Models\Doacao;
use App\Models\Item;
use App\Models\Necessidade;
use App\Models\User;
use App\Models\Voluntario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ===== Usuarios da equipe =====
        User::updateOrCreate(['email' => 'admin@pagodedadonamaria.org'], [
            'name' => 'Dona Maria (Coordenação)',
            'password' => Hash::make('senha123'),
            'role' => User::ROLE_ADMIN,
            'ativo' => true,
        ]);

        User::updateOrCreate(['email' => 'voluntario@pagodedadonamaria.org'], [
            'name' => 'João Voluntário',
            'password' => Hash::make('senha123'),
            'role' => User::ROLE_VOLUNTARIO,
            'ativo' => true,
        ]);

        // ===== Dados da associacao =====
        Configuracao::instancia()->update([
            'nome' => 'Pagode da Dona Maria',
            'sobre_trabalho' => 'Ajudamos crianças e famílias da comunidade com alimentos, roupas, material escolar e muito carinho.',
            'historia' => "O Pagode da Dona Maria nasceu do sonho de transformar o samba em solidariedade. O que começou com uma roda de amigos virou um ponto de apoio para dezenas de famílias.",
            'endereco' => 'Rua do Samba, 100 — Centro',
            'cidade' => 'São Paulo',
            'estado' => 'SP',
            'telefone' => '(11) 3333-0000',
            'whatsapp' => '5511999990000',
            'instagram' => '@pagodedadonamaria',
            'horario_atendimento' => 'Seg a Sex, 9h às 17h',
            'pix_chave' => 'contato@pagodedadonamaria.org',
            'pix_tipo' => 'email',
            'pix_nome_recebedor' => 'Associação Pagode da Dona Maria',
            'pix_cidade' => 'Sao Paulo',
            'criancas_atendidas' => 120,
            'familias_atendidas' => 45,
        ]);

        // ===== Categorias =====
        $cats = [];
        foreach ([
            ['Alimentos', '🍚'], ['Roupas', '👕'], ['Calçados', '👟'],
            ['Brinquedos', '🧸'], ['Material escolar', '🎒'], ['Higiene', '🧼'],
            ['Dinheiro', '💰'],
        ] as [$nome, $icone]) {
            $cats[$nome] = Categoria::updateOrCreate(['nome' => $nome], ['icone' => $icone]);
        }

        // ===== Itens de estoque (com um baixo pra mostrar alerta) =====
        $itens = [
            ['Arroz', 'Alimentos', 'kg', 35, 20],
            ['Feijão', 'Alimentos', 'kg', 12, 20],        // ABAIXO do minimo
            ['Leite', 'Alimentos', 'caixa', 63, 50],
            ['Caderno', 'Material escolar', 'un', 80, 30],
            ['Sabonete', 'Higiene', 'un', 18, 25],        // ABAIXO do minimo
        ];
        foreach ($itens as [$nome, $cat, $un, $qtd, $min]) {
            Item::updateOrCreate(['nome' => $nome], [
                'categoria_id' => $cats[$cat]->id,
                'unidade' => $un,
                'quantidade' => $qtd,
                'quantidade_minima' => $min,
            ]);
        }

        // ===== Campanhas =====
        $campanhas = [
            ['Natal das Crianças', 'dinheiro', 5000, null, 3200, true],
            ['Volta às Aulas', 'itens', 200, 'kits', 128, true],
            ['Campanha de Alimentos', 'itens', 500, 'kg', 342, true],
        ];
        foreach ($campanhas as [$titulo, $tipo, $meta, $un, $arr, $ativa]) {
            Campanha::updateOrCreate(['titulo' => $titulo], [
                'tipo_meta' => $tipo,
                'meta' => $meta,
                'unidade_meta' => $un,
                'arrecadado' => $arr,
                'ativa' => $ativa,
                'publica' => true,
                'data_inicio' => Carbon::now()->subDays(20),
                'descricao' => 'Ajude a gente a alcançar essa meta e transformar a vida das nossas crianças. 💜',
            ]);
        }

        // ===== Necessidades =====
        $necessidades = [
            ['Leite', 'urgente', 100, 'caixas', 63],
            ['Material escolar', 'alta', 50, 'kits', 22],
            ['Agasalhos infantis', 'media', 80, 'un', 31],
        ];
        foreach ($necessidades as [$titulo, $prio, $meta, $un, $arr]) {
            Necessidade::updateOrCreate(['titulo' => $titulo], [
                'prioridade' => $prio,
                'meta' => $meta,
                'unidade' => $un,
                'arrecadado' => $arr,
                'publica' => true,
                'concluida' => false,
                'prazo' => Carbon::now()->addDays(30),
            ]);
        }

        // ===== Voluntarios =====
        Voluntario::updateOrCreate(['nome' => 'Maria da Silva'], [
            'whatsapp' => '5511988887777',
            'areas' => ['arrecadacao', 'distribuicao'],
            'disponibilidade' => 'Fins de semana',
            'regiao' => 'Centro',
            'ativo' => true,
        ]);
        Voluntario::updateOrCreate(['nome' => 'Carlos Souza'], [
            'whatsapp' => '5511977776666',
            'areas' => ['transporte', 'eventos'],
            'disponibilidade' => 'Noites',
            'regiao' => 'Zona Leste',
            'ativo' => true,
        ]);

        // ===== Doacoes recentes =====
        Doacao::updateOrCreate(['descricao' => 'Doação de alimentos (seed)'], [
            'tipo' => 'item', 'categoria_id' => $cats['Alimentos']->id,
            'doador_nome' => 'Padaria do Bairro', 'quantidade' => 15, 'unidade' => 'kg',
            'data' => Carbon::now()->subDays(2),
        ]);
        Doacao::updateOrCreate(['descricao' => 'Doação em dinheiro (seed)'], [
            'tipo' => 'dinheiro', 'valor' => 250, 'anonimo' => true,
            'data' => Carbon::now()->subDay(),
        ]);

        // ===== Arrecadacao na rua =====
        Arrecadacao::updateOrCreate(['titulo' => 'Arrecadação no Centro'], [
            'local' => 'Centro', 'data' => Carbon::now()->subDays(3),
            'total_dinheiro' => 387, 'total_itens' => 67, 'encerrada' => true,
        ]);
    }
}
