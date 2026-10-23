<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Historico de movimentacoes do estoque (seção 3).
     * Entrada aumenta saldo, saida diminui. Mantem rastreabilidade.
     */
    public function up(): void
    {
        Schema::create('movimentacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('itens')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('tipo', ['entrada', 'saida']);
            $table->decimal('quantidade', 12, 2);
            $table->string('origem')->nullable(); // doacao, compra, distribuicao, ajuste
            $table->nullableMorphs('referencia'); // vinculo opcional (doacao, arrecadacao...)
            $table->text('observacao')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimentacoes');
    }
};
