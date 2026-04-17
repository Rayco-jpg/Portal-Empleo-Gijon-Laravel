<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('usuarios', function (Blueprint $table) {
            // Añadimos los campos necesarios para la recuperación
            $table->string('reset_token')->nullable()->after('password');
            $table->datetime('token_expira')->nullable()->after('reset_token');
        });
    }

    public function down()
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn(['reset_token', 'token_expira']);
        });
    }
};
