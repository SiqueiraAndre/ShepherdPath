<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('presencas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('catequizando_id')->constrained('catequizandos')->onDelete('cascade');
            $table->foreignId('missa_id')->constrained('missas');
            $table->date('data_missa'); // A data do evento
            $table->timestamps();
            
            // Index para otimizar a busca semanal que você faz
            $table->index(['data_missa', 'catequizando_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presencas');
    }
};
