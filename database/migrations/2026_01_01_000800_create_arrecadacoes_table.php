<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Acoes de arrecadacao na rua (seção 8). Ex: "Arrecadacao 22/10 - Centro".
     * Agrupa as doacoes recebidas naquela acao.
     */
    public function up(): void
    {
        Schema::create('arrecadacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voluntario_id')->nullable()->constrained('voluntarios')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('titulo')->nullable();
            $table->string('local')->nullable();
            $table->date('data');
            $table->decimal('total_dinheiro', 12, 2)->default(0);
            $table->unsignedInteger('total_itens')->default(0);
            $table->text('observacao')->nullable();
            $table->boolean('encerrada')->default(false);
            $table->timestamps();
        });

        // Vincula doacoes a uma acao de arrecadacao (opcional).
        Schema::table('doacoes', function (Blueprint $table) {
            $table->foreignId('arrecadacao_id')->nullable()->after('campanha_id')
                ->constrained('arrecadacoes')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('doacoes', function (Blueprint $table) {
            $table->dropConstrainedForeignId('arrecadacao_id');
        });
        Schema::dropIfExists('arrecadacoes');
    }
};
