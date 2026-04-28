<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('empresas'); 
        
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usuario')->constrained('users')->onDelete('cascade');
            
            $table->string('nombre_empresa');
            $table->string('sector')->nullable();
            $table->string('tamano')->nullable();
            $table->string('ubicacion')->nullable();
            $table->string('sitio_web')->nullable();
            $table->string('twitter')->nullable();
            $table->text('descripcion')->nullable();
            $table->string('logo')->nullable(); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
