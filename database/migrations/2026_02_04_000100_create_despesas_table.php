<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * V3 — Controle de despesas da associacao (luz, agua, aluguel, material...).
     * Permite o relatorio financeiro de RECEITAS x DESPESAS por periodo.
     */
    public function up(): void
    {
        Schema::create('despesas', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->string('categoria')->nullable(); // Luz, Agua, Aluguel, Material, Mao de obra...
            $table->decimal('valor', 12, 2);
            $table->date('data');
            $table->boolean('paga')->default(true);
            $table->boolean('recorrente')->default(false); // conta fixa mensal
            $table->foreignId('registrado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->text('observacao')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('despesas');
    }
};
