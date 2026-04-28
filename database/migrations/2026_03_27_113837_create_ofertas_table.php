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
            $table->id(); 
            $table->string('titulo');
            $table->text('descripcion');
            $table->foreignId('id_categoria')->constrained('categorias', 'id_categoria');
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
