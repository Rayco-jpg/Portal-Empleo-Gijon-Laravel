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
            
            // CAMBIO AQUÍ: Cambiamos 'users' por 'usuarios'
            $table->foreignId('id_usuario')
                  ->constrained('usuarios') 
                  ->onDelete('cascade');
                  
            $table->string('nombre');
            $table->string('apellidos');
            $table->string('foto')->nullable();
            $table->string('curriculum')->nullable();
            $table->text('habilidades_clave')->nullable();
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
