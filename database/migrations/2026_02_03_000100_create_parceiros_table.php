<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** V2 — Parceiros da associacao (Barbearia do Cleber, Oficina do Tiao...). */
    public function up(): void
    {
        Schema::create('parceiros', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('categoria')->nullable(); // Barbearia, Oficina, Mercado...
            $table->string('logo')->nullable();
            $table->string('site')->nullable();       // link ou @instagram
            $table->string('frase')->nullable();      // "Apoia nossas crianças"
            $table->unsignedInteger('ordem')->default(0);
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parceiros');
    }
};
