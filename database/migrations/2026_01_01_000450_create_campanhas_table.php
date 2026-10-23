<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Campanhas de arrecadacao (seção 5). Ex: Natal das Criancas, Volta as Aulas.
     * Meta pode ser em dinheiro (R$) ou em itens (quantidade).
     */
    public function up(): void
    {
        Schema::create('campanhas', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('slug')->unique();
            $table->text('descricao')->nullable();
            $table->string('foto')->nullable();
            // meta em valor OU em quantidade de itens
            $table->enum('tipo_meta', ['dinheiro', 'itens'])->default('dinheiro');
            $table->decimal('meta', 12, 2)->nullable();
            $table->string('unidade_meta')->nullable(); // ex: caixas, kg
            $table->decimal('arrecadado', 12, 2)->default(0);
            $table->date('data_inicio')->nullable();
            $table->date('data_fim')->nullable();
            $table->boolean('ativa')->default(true);
            $table->boolean('publica')->default(true); // aparece na area publica
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campanhas');
    }
};
