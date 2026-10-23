<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Dados institucionais da associacao (seção 1 do checklist).
     * Tabela de linha unica — editada no painel administrativo.
     */
    public function up(): void
    {
        Schema::create('configuracoes', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->default('Pagode da Dona Maria');
            $table->string('logo')->nullable();
            $table->text('historia')->nullable();
            $table->text('sobre_trabalho')->nullable();
            $table->string('endereco')->nullable();
            $table->string('cidade')->nullable();
            $table->string('estado', 2)->nullable();
            $table->string('telefone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('instagram')->nullable();
            $table->string('facebook')->nullable();
            $table->string('email')->nullable();
            $table->string('horario_atendimento')->nullable();
            // Pix (seção 6)
            $table->string('pix_chave')->nullable();
            $table->string('pix_tipo')->nullable(); // cpf, cnpj, email, telefone, aleatoria
            $table->string('pix_nome_recebedor')->nullable();
            $table->string('pix_cidade')->nullable();
            // Impacto (seção 14) — numeros agregados, SEM dados de criancas
            $table->unsignedInteger('criancas_atendidas')->default(0);
            $table->unsignedInteger('familias_atendidas')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configuracoes');
    }
};
