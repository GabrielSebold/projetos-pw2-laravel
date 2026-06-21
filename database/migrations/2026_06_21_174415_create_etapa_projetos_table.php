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
        Schema::create('etapa_projetos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('projeto_id')->constrained('projetos')->cascadeOnDelete();
            $table->string('titulo');
            $table->date('data_registro');
            $table->enum('status', ['Planejada', 'Em execução', 'Concluída', 'Bloqueada'])->default('Planejada');
            $table->text('descricao');
            $table->text('problematica')->nullable();
            $table->text('metodo_gestao')->nullable();
            $table->unsignedInteger('ordem')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etapa_projetos');
    }
};
