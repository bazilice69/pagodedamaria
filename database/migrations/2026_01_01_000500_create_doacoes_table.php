<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Registro de doacoes recebidas (seção 2).
     * Doador é opcional (pode ser anonimo). Pode vincular a campanha e item.
     */
    public function up(): void
    {
        Schema::create('doacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campanha_id')->nullable()->constrained('campanhas')->nullOnDelete();
            $table->foreignId('categoria_id')->nullable()->constrained('categorias')->nullOnDelete();
            $table->foreignId('item_id')->nullable()->constrained('itens')->nullOnDelete();
            $table->foreignId('recebido_por')->nullable()->constrained('users')->nullOnDelete();
            // tipo: dinheiro ou item fisico
            $table->enum('tipo', ['dinheiro', 'item'])->default('item');
            $table->string('doador_nome')->nullable();
            $table->string('doador_contato')->nullable();
            $table->boolean('anonimo')->default(false);
            $table->string('descricao')->nullable();
            $table->decimal('quantidade', 12, 2)->nullable();
            $table->string('unidade')->nullable();
            $table->decimal('valor', 12, 2)->nullable(); // quando tipo = dinheiro
            $table->string('comprovante')->nullable();    // foto/anexo
            $table->boolean('entrou_estoque')->default(false);
            $table->text('observacao')->nullable();
            $table->date('data')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doacoes');
    }
};
