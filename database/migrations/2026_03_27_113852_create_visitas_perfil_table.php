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
        Schema::create('visitas_perfil', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_candidato')
                  ->constrained('candidatos', 'id_candidato')
                  ->onDelete('cascade');
            $table->foreignId('id_empresa')
                  ->constrained('usuarios') 
                  ->onDelete('cascade');
                  
            $table->timestamp('fecha_visita')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitas_perfil');
    }
};
