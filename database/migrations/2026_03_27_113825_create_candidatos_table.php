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
        Schema::create('candidatos', function (Blueprint $table) {
            $table->id('id_candidato');
            $table->foreignId('id_usuario')
                ->constrained('usuarios')
                ->onDelete('cascade');

            $table->string('nombre');
            $table->string('apellidos');
            $table->string('ubicacion')->nullable();
            $table->string('foto')->nullable();
            $table->string('curriculum')->nullable();
            $table->text('biografia')->nullable();
            $table->text('habilidades_clave')->nullable();
            $table->boolean('disponible')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidatos');
    }
};
