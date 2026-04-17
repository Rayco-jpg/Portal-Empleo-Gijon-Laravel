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
        Schema::create('ofertas', function (Blueprint $table) {
            $table->id(); // Este es el id_oferta
            $table->string('titulo');
            $table->text('descripcion');
            
            // 1. Relación con categorías (Esta ya estaba bien)
            $table->foreignId('id_categoria')->constrained('categorias', 'id_categoria');
            
            // 2. CAMBIO CLAVE: Apuntamos a 'usuarios' (que es donde están las empresas)
            $table->foreignId('id_empresa')
                  ->constrained('usuarios') 
                  ->onDelete('cascade');

            $table->string('zona_gijon')->nullable();
            $table->decimal('salario', 10, 2)->nullable();
            $table->string('jornada')->nullable();
            $table->string('experiencia')->nullable();
            $table->string('latitud')->nullable();
            $table->string('longitud')->nullable();
            $table->date('fecha_oferta');
            $table->string('estado')->default('activa');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ofertas');
    }
};
