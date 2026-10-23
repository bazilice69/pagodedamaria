<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Itens de estoque (seção 3). Cada item tem saldo atual e minimo
     * para disparar o alerta de estoque baixo.
     */
    public function up(): void
    {
        Schema::create('itens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_id')->nullable()->constrained('categorias')->nullOnDelete();
            $table->string('nome');
            $table->string('unidade')->default('un'); // kg, un, caixa, litro...
            $table->decimal('quantidade', 12, 2)->default(0);
            $table->decimal('quantidade_minima', 12, 2)->default(0);
            $table->text('observacao')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('itens');
    }
};
