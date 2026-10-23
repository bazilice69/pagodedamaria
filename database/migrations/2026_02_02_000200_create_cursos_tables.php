<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * V2 — Modulo educacional: categorias de curso (editaveis pelos donos),
     * cursos/turmas, matriculas (aluno em varios cursos) e presenca (chamada).
     */
    public function up(): void
    {
        // Categorias de curso — os donos criam novas quando quiserem.
        Schema::create('categorias_curso', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('icone')->nullable();
            $table->string('cor')->nullable();
            $table->timestamps();
        });

        Schema::create('cursos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_curso_id')->nullable()->constrained('categorias_curso')->nullOnDelete();
            $table->foreignId('professor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nome'); // ex: Judô infantil, Balé turma A
            $table->text('descricao')->nullable();
            $table->string('local')->nullable();
            $table->string('dias_semana')->nullable(); // ex: "Seg, Qua"
            $table->string('horario')->nullable();     // ex: "14h às 15h"
            $table->unsignedInteger('vagas')->nullable();
            $table->unsignedInteger('idade_min')->nullable();
            $table->unsignedInteger('idade_max')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('matriculas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pessoa_id')->constrained('pessoas')->cascadeOnDelete();
            $table->foreignId('curso_id')->constrained('cursos')->cascadeOnDelete();
            $table->date('data_matricula')->nullable();
            $table->string('status')->default('ativa'); // ativa, trancada, concluida, cancelada
            $table->text('observacao')->nullable();
            $table->timestamps();
            $table->unique(['pessoa_id', 'curso_id']); // nao matricula 2x no mesmo curso
        });

        Schema::create('presencas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id')->constrained('cursos')->cascadeOnDelete();
            $table->foreignId('pessoa_id')->constrained('pessoas')->cascadeOnDelete();
            $table->foreignId('registrado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->date('data');
            $table->boolean('presente')->default(true);
            $table->text('observacao')->nullable();
            $table->timestamps();
            $table->unique(['curso_id', 'pessoa_id', 'data']); // 1 registro por aluno/dia/curso
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presencas');
        Schema::dropIfExists('matriculas');
        Schema::dropIfExists('cursos');
        Schema::dropIfExists('categorias_curso');
    }
};
