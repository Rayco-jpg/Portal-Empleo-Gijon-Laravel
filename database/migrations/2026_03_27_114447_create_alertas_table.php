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
        Schema::create('alertas', function (Blueprint $table) {
            $table->id('id_alerta');

            // CAMBIO AQUÍ: Apuntamos a 'usuarios' en vez de 'users'
            $table->foreignId('id_usuario')
                  ->constrained('usuarios')
                  ->onDelete('cascade');

            // Relación con la categoría (esta ya estaba bien)
            $table->foreignId('id_categoria')
                  ->constrained('categorias', 'id_categoria')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alertas');
    }
};
