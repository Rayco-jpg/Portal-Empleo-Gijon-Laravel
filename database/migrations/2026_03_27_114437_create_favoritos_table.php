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
            $table->foreignId('id_usuario')
                  ->constrained('usuarios') 
                  ->onDelete('cascade');
            $table->foreignId('id_oferta')
                  ->constrained('ofertas')
                  ->onDelete('cascade');
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