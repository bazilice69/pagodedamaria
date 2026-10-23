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
        // ===== Usuarios da equipe (hierarquia V2) =====
        User::updateOrCreate(['email' => 'admin@pagodedadonamaria.org'], [
            'name' => 'Dona Maria',
            'password' => Hash::make('senha123'),
            'role' => User::ROLE_PRESIDENTE,
            'cargo' => 'Presidente',
            'ativo' => true,
            'status' => User::STATUS_APROVADO,
        ]);

        User::updateOrCreate(['email' => 'diretor@pagodedadonamaria.org'], [
            'name' => 'Carlos Diretor',
            'password' => Hash::make('senha123'),
            'role' => User::ROLE_DIRETOR,
            'cargo' => 'Diretor Social',
            'ativo' => true,
            'status' => User::STATUS_APROVADO,
        ]);

        User::updateOrCreate(['email' => 'voluntario@pagodedadonamaria.org'], [
            'name' => 'João Voluntário',
            'password' => Hash::make('senha123'),
            'role' => User::ROLE_VOLUNTARIO,
            'cargo' => 'Voluntário de Arrecadação',
            'ativo' => true,
            'status' => User::STATUS_APROVADO,
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

        // ===== Voluntarios (ja aprovados) =====
        Voluntario::updateOrCreate(['nome' => 'Maria da Silva'], [
            'whatsapp' => '5511988887777',
            'areas' => ['arrecadacao', 'distribuicao'],
            'disponibilidade' => 'Fins de semana',
            'regiao' => 'Centro',
            'ativo' => true,
            'status' => Voluntario::STATUS_APROVADO,
            'funcao' => 'Voluntária de Distribuição',
            'aprovado_em' => Carbon::now()->subDays(10),
        ]);
        Voluntario::updateOrCreate(['nome' => 'Carlos Souza'], [
            'whatsapp' => '5511977776666',
            'areas' => ['transporte', 'eventos'],
            'disponibilidade' => 'Noites',
            'regiao' => 'Zona Leste',
            'ativo' => true,
            'status' => Voluntario::STATUS_APROVADO,
            'funcao' => 'Voluntário de Transporte',
            'aprovado_em' => Carbon::now()->subDays(8),
        ]);

        // ===== Candidaturas PENDENTES (para mostrar o fluxo de aprovacao 🔔) =====
        Voluntario::updateOrCreate(['nome' => 'Ana Pereira'], [
            'whatsapp' => '5511966665555',
            'email' => 'ana@exemplo.com',
            'areas' => ['comunicacao', 'eventos'],
            'disponibilidade' => 'Tardes',
            'regiao' => 'Zona Norte',
            'ativo' => false,
            'status' => Voluntario::STATUS_PENDENTE,
        ]);
        Voluntario::updateOrCreate(['nome' => 'Pedro Lima'], [
            'whatsapp' => '5511955554444',
            'areas' => ['arrecadacao'],
            'disponibilidade' => 'Sábados',
            'regiao' => 'Centro',
            'ativo' => false,
            'status' => Voluntario::STATUS_PENDENTE,
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

        // ===== V2: Tipos de beneficio (sugestoes editaveis) =====
        foreach (\App\Models\TipoBeneficio::SUGESTOES as $nome) {
            \App\Models\TipoBeneficio::updateOrCreate(['nome' => $nome], ['ativo' => true]);
        }

        // ===== V2: Categorias de curso =====
        $catCursos = [];
        foreach ([['Esportes', '🥋'], ['Música', '🎵'], ['Educação', '📚'], ['Arte', '🎨']] as [$nome, $icone]) {
            $catCursos[$nome] = \App\Models\CategoriaCurso::updateOrCreate(['nome' => $nome], ['icone' => $icone]);
        }

        // ===== V2: Pessoas (familia que DOA e RECEBE + alunos) =====
        $maeSilva = \App\Models\Pessoa::updateOrCreate(['nome' => 'Joana Silva'], [
            'whatsapp' => '5511944443333',
            'bairro' => 'Centro', 'cidade' => 'São Paulo', 'estado' => 'SP',
            'e_responsavel' => true, 'e_doador' => true, 'e_beneficiario' => true, // doa E recebe
            'autoriza_imagem' => true, 'autoriza_imagem_data' => Carbon::now(),
            'ativo' => true,
        ]);
        // Beneficio da familia Silva
        \App\Models\BeneficioPessoa::updateOrCreate(
            ['pessoa_id' => $maeSilva->id, 'tipo_livre' => 'Bolsa Família'],
            ['valor' => 600, 'ativo' => true]
        );

        $alunos = [];
        foreach ([
            ['Lucas Silva', '2015-03-10', $maeSilva->id, 'filho'],
            ['Beatriz Silva', '2017-07-22', $maeSilva->id, 'filha'],
            ['Gabriel Santos', '2014-11-05', null, null],
        ] as [$nome, $nasc, $respId, $parentesco]) {
            $alunos[] = \App\Models\Pessoa::updateOrCreate(['nome' => $nome], [
                'nascimento' => $nasc,
                'e_aluno' => true,
                'responsavel_id' => $respId,
                'parentesco' => $parentesco,
                'autoriza_imagem' => true, 'autoriza_imagem_data' => Carbon::now(),
                'ativo' => true,
            ]);
        }

        // ===== V2: Cursos =====
        $profId = User::where('email', 'diretor@pagodedadonamaria.org')->value('id');
        $judo = \App\Models\Curso::updateOrCreate(['nome' => 'Judô Infantil'], [
            'categoria_curso_id' => $catCursos['Esportes']->id,
            'professor_id' => $profId,
            'dias_semana' => 'Ter, Qui', 'horario' => '14h às 15h',
            'local' => 'Quadra', 'vagas' => 20, 'idade_min' => 6, 'idade_max' => 12,
            'ativo' => true,
        ]);
        $musica = \App\Models\Curso::updateOrCreate(['nome' => 'Música / Pagode'], [
            'categoria_curso_id' => $catCursos['Música']->id,
            'professor_id' => $profId,
            'dias_semana' => 'Sáb', 'horario' => '10h às 12h',
            'local' => 'Salão', 'vagas' => 15, 'ativo' => true,
        ]);

        // ===== V2: Matriculas (crianca em VARIOS cursos) =====
        foreach ($alunos as $aluno) {
            \App\Models\Matricula::updateOrCreate(
                ['pessoa_id' => $aluno->id, 'curso_id' => $judo->id],
                ['status' => 'ativa', 'data_matricula' => Carbon::now()->subDays(5)]
            );
        }
        // Lucas tambem faz musica (varios cursos)
        \App\Models\Matricula::updateOrCreate(
            ['pessoa_id' => $alunos[0]->id, 'curso_id' => $musica->id],
            ['status' => 'ativa', 'data_matricula' => Carbon::now()->subDays(5)]
        );

        // ===== V2: Parceiros =====
        \App\Models\Parceiro::updateOrCreate(['nome' => 'Barbearia do Cleber'], [
            'categoria' => 'Barbearia', 'frase' => 'Apoia nossas crianças 💜', 'ordem' => 1, 'ativo' => true,
        ]);
        \App\Models\Parceiro::updateOrCreate(['nome' => 'Oficina do Tião'], [
            'categoria' => 'Oficina mecânica', 'frase' => 'Parceiro do bem', 'ordem' => 2, 'ativo' => true,
        ]);

        // ===== V2: Modelos de termo (exemplos) =====
        \App\Models\ModeloTermo::updateOrCreate(['titulo' => 'Autorização de Passeio'], [
            'tipo' => 'passeio',
            'conteudo' => "Eu, {{responsavel}}, responsável pelo(a) menor {{nome}} (matrícula {{codigo}}), ".
                "autorizo sua participação no passeio organizado pela {{associacao}}.\n\n".
                "Declaro estar ciente das atividades e autorizo o transporte e a supervisão pela equipe da associação.\n\n".
                "{{associacao}}, {{data}}.",
            'ativo' => true,
        ]);
        \App\Models\ModeloTermo::updateOrCreate(['titulo' => 'Autorização de Uso de Imagem'], [
            'tipo' => 'imagem',
            'conteudo' => "Eu, {{responsavel}}, responsável pelo(a) {{nome}} (matrícula {{codigo}}), ".
                "autorizo a {{associacao}} a utilizar a imagem do(a) menor em materiais de divulgação ".
                "das atividades sociais, sem fins lucrativos.\n\n".
                "Esta autorização é concedida de forma gratuita.\n\n".
                "{{associacao}}, {{data}}.",
            'ativo' => true,
        ]);
    }
}
