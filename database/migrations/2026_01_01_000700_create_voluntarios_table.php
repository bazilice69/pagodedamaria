<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Cadastro de voluntarios (seção 7). V1 ja inclui o cadastro
     * (o formulario publico de "quero ser voluntario" alimenta aqui).
     */
    public function up(): void
    {
        Schema::create('voluntarios', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('whatsapp')->nullable();
            $table->string('email')->nullable();
            // areas de atuacao: arrecadacao, transporte, organizacao, distribuicao, eventos, comunicacao, outros
            $table->json('areas')->nullable();
            $table->string('disponibilidade')->nullable();
            $table->string('regiao')->nullable();
            $table->text('observacao')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voluntarios');
    }
};
