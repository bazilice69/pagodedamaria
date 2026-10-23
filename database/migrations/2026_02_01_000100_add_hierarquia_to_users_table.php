<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * V2 — hierarquia real das associacoes.
     * role passa a aceitar: presidente, vice, diretor, professor, voluntario.
     * Acrescenta aprovacao (status) e vinculo opcional a uma pessoa (ficha).
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Cargo/titulo social que aparece na carteirinha (texto livre).
            $table->string('cargo')->nullable()->after('role');
            // Fluxo de aprovacao: pendente -> aprovado/recusado.
            $table->string('status')->default('aprovado')->after('ativo');
            $table->foreignId('aprovado_por')->nullable()->after('status')
                ->constrained('users')->nullOnDelete();
            $table->timestamp('aprovado_em')->nullable()->after('aprovado_por');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('aprovado_por');
            $table->dropColumn(['cargo', 'status', 'aprovado_em']);
        });
    }
};
