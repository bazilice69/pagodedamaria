<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * V2 — rastreabilidade total. Resolve o problema do "deu 14kg pro Joao,
     * quem deu? era so 5kg". Toda SAIDA registra para QUEM foi (destino) e,
     * via user_id (ja existente), QUEM entregou. Entradas registram de ONDE veio.
     */
    public function up(): void
    {
        Schema::table('movimentacoes', function (Blueprint $table) {
            // Nome de quem recebeu (saida) ou de quem trouxe (entrada).
            $table->string('destinatario')->nullable()->after('origem');
            // Documento/telefone opcional de quem recebeu, para conferencia.
            $table->string('destinatario_contato')->nullable()->after('destinatario');
        });
    }

    public function down(): void
    {
        Schema::table('movimentacoes', function (Blueprint $table) {
            $table->dropColumn(['destinatario', 'destinatario_contato']);
        });
    }
};
