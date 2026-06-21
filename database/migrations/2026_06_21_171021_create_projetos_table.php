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
        Schema::create('projetos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('categoria_id')->constrained('categorias');
            $table->string('titulo');
            $table->string('responsavel');
            $table->text('resumo');
            $table->enum('status', ['Planejamento', 'Em andamento', 'Concluído', 'Pausado'])->default('Planejamento');
            $table->unsignedSmallInteger('ano_letivo');
            $table->unsignedTinyInteger('periodo_letivo');
            $table->date('data_inicio')->nullable();
            $table->date('data_entrega')->nullable();
            $table->string('imagem')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projetos');
    }
};
