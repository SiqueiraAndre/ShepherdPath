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
        Schema::create('link_acessos', function (Blueprint $table) {
            $table->id();
            $table->string('hash')->unique();
            $table->string('descricao')->nullable();
            $table->integer('acessos')->default(0);
            $table->dateTime('expira_em')->nullable();
            $table->boolean('is_ativo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('link_acessos');
    }
};
