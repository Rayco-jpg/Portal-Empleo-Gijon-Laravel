<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    // Esto es necesario si usas campos ENUM, si es un STRING normal no hace falta, 
    // pero te aseguras de que el sistema acepte 'admin'.
    DB::statement("ALTER TABLE usuarios MODIFY COLUMN tipo_usuario ENUM('candidato', 'empresa', 'admin') NOT NULL");
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            //
        });
    }
};
