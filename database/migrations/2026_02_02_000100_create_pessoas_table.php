<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * V2 — Cadastro UNICO de pessoa (ficha estilo igreja).
     * Uma mesma pessoa pode ter varios papeis (aluno, responsavel, voluntario,
     * doador, beneficiario) — resolve o caso "familia que doa E recebe".
     * Gera ID unico (matricula) e serve de base para a carteirinha com QR Code.
     */
    public function up(): void
    {
        Schema::create('pessoas', function (Blueprint $table) {
            $table->id();
            // Codigo/matricula unica exibida na carteirinha (ex: PDM-000001).
            $table->string('codigo')->unique()->nullable();

            // Dados pessoais
            $table->string('nome');
            $table->string('apelido')->nullable();
            $table->date('nascimento')->nullable();
            $table->string('sexo')->nullable();
            $table->string('cpf')->nullable();
            $table->string('rg')->nullable();
            $table->string('foto')->nullable();

            // Contato
            $table->string('telefone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('email')->nullable();

            // Endereco (preenchido por CEP)
            $table->string('cep', 9)->nullable();
            $table->string('logradouro')->nullable();
            $table->string('numero')->nullable();
            $table->string('complemento')->nullable();
            $table->string('bairro')->nullable();
            $table->string('cidade')->nullable();
            $table->string('estado', 2)->nullable();

            // Papeis (flags) — a pessoa pode ser varias coisas ao mesmo tempo
            $table->boolean('e_aluno')->default(false);
            $table->boolean('e_responsavel')->default(false);
            $table->boolean('e_voluntario')->default(false);
            $table->boolean('e_doador')->default(false);
            $table->boolean('e_beneficiario')->default(false);

            // Autorizacao de uso de imagem (seção 13 — cuidado com criancas!)
            $table->boolean('autoriza_imagem')->default(false);
            $table->date('autoriza_imagem_data')->nullable();

            // Responsavel (para alunos menores de idade)
            $table->foreignId('responsavel_id')->nullable()->constrained('pessoas')->nullOnDelete();
            $table->string('parentesco')->nullable(); // mae, pai, avo, tio...

            // Conta de acesso vinculada (professor/voluntario que loga)
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->text('observacao')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pessoas');
    }
};
