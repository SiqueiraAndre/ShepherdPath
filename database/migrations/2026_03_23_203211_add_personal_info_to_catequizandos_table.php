<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('catequizandos', function (Blueprint $table) {
            $table->date('data_nascimento')->nullable();
            $table->string('telefone')->nullable();
            $table->string('nome_responsavel')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('catequizandos', function (Blueprint $table) {
            $table->dropColumn(['data_nascimento', 'telefone', 'nome_responsavel']);
        });
    }
};
