<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * V2 — Termos de autorizacao (passeio, uso de imagem...).
     * A associacao cadastra MODELOS com placeholders; ao gerar para uma pessoa,
     * o sistema auto-preenche e PRESERVA o conteudo daquele momento.
     */
    public function up(): void
    {
        Schema::create('modelos_termo', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');                 // ex: Autorizacao de passeio
            $table->string('tipo')->default('geral'); // passeio, imagem, geral
            $table->longText('conteudo');             // texto com {{placeholders}}
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('termos_gerados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modelo_termo_id')->nullable()->constrained('modelos_termo')->nullOnDelete();
            $table->foreignId('pessoa_id')->nullable()->constrained('pessoas')->nullOnDelete();
            $table->foreignId('gerado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->string('titulo');
            $table->longText('conteudo'); // ja preenchido — congelado neste momento
            $table->string('status')->default('gerado'); // gerado, assinado
            $table->timestamp('assinado_em')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('termos_gerados');
        Schema::dropIfExists('modelos_termo');
    }
};
