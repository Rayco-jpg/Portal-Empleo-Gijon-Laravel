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
        Schema::create('favoritos', function (Blueprint $table) {
            $table->id();
            
            // Relación con tu tabla 'usuarios'
            $table->foreignId('id_usuario')
                  ->constrained('usuarios') 
                  ->onDelete('cascade');

            // Relación con la tabla 'ofertas'
            $table->foreignId('id_oferta')
                  ->constrained('ofertas')
                  ->onDelete('cascade');

            // Añadimos esta columna para que tu controlador NO de error al ordenar
            $table->timestamp('fecha_guardado')->useCurrent(); 

            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favoritos');
    }
};