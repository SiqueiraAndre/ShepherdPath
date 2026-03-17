<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agendamentos_relatorio', function (Blueprint $table) {
            $table->id();
            $table->json('destinatarios');
            $table->dateTime('data_envio');
            $table->date('periodo_inicio');
            $table->date('periodo_fim');
            $table->string('assunto');
            $table->text('mensagem');
            $table->enum('status', ['pendente', 'enviado', 'falhou'])->default('pendente');
            $table->dateTime('enviado_em')->nullable();
            $table->text('erro')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agendamentos_relatorio');
    }
};
