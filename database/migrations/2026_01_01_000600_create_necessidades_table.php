<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Lista de necessidades (seção 4). "Estamos precisando de X".
     * Mostra progresso (arrecadado / meta) na area publica.
     */
    public function up(): void
    {
        Schema::create('necessidades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_id')->nullable()->constrained('categorias')->nullOnDelete();
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->decimal('meta', 12, 2)->default(0);
            $table->string('unidade')->default('un');
            $table->decimal('arrecadado', 12, 2)->default(0);
            $table->enum('prioridade', ['baixa', 'media', 'alta', 'urgente'])->default('media');
            $table->date('prazo')->nullable();
            $table->boolean('concluida')->default(false);
            $table->boolean('publica')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('necessidades');
    }
};
