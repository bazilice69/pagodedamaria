<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * V2 — candidatura de voluntario passa por aprovacao.
     * Quem se candidata pelo site entra como "pendente" e NAO ganha acesso
     * ate um gestor (presidente/vice/diretor) aprovar.
     */
    public function up(): void
    {
        Schema::table('voluntarios', function (Blueprint $table) {
            $table->string('status')->default('pendente')->after('ativo');
            $table->string('funcao')->nullable()->after('status'); // ex: "Voluntário de Arrecadação"
            $table->foreignId('user_id')->nullable()->after('funcao')
                ->constrained('users')->nullOnDelete(); // conta de acesso, se o gestor gerar
            $table->foreignId('aprovado_por')->nullable()->after('user_id')
                ->constrained('users')->nullOnDelete();
            $table->timestamp('aprovado_em')->nullable()->after('aprovado_por');
        });
    }

    public function down(): void
    {
        Schema::table('voluntarios', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
            $table->dropConstrainedForeignId('aprovado_por');
            $table->dropColumn(['status', 'funcao', 'aprovado_em']);
        });
    }
};
