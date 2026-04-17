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
        Schema::create('empresas', function (Blueprint $table) {
            $table->id('id_empresa');
            
            // CAMBIO CLAVE: Apuntamos a 'usuarios', no a 'users'
            $table->foreignId('id_usuario')
                  ->constrained('usuarios') 
                  ->onDelete('cascade');
                  
            $table->string('nombre_empresa');
            $table->string('logo')->nullable();
            $table->string('sector')->nullable(); // Añadida porque tu controlador la usa
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
