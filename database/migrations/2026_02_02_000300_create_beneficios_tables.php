<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * V2 — Beneficios sociais recebidos pela familia/pessoa.
     * Ajuda a entender a vulnerabilidade (contexto social) e direcionar a ajuda.
     * "tipos_beneficio" vem com sugestoes (Bolsa Familia, BPC/LOAS...) mas os
     * donos podem ADICIONAR/EDITAR categorias livremente.
     */
    public function up(): void
    {
        Schema::create('tipos_beneficio', function (Blueprint $table) {
            $table->id();
            $table->string('nome');            // ex: Bolsa Família, BPC/LOAS
            $table->string('descricao')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('beneficios_pessoa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pessoa_id')->constrained('pessoas')->cascadeOnDelete();
            $table->foreignId('tipo_beneficio_id')->nullable()->constrained('tipos_beneficio')->nullOnDelete();
            $table->string('tipo_livre')->nullable();   // caso nao use um tipo cadastrado
            $table->decimal('valor', 12, 2)->nullable(); // valor mensal, se souber
            $table->string('numero_nis')->nullable();    // NIS/identificacao, opcional
            $table->text('observacao')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beneficios_pessoa');
        Schema::dropIfExists('tipos_beneficio');
    }
};
