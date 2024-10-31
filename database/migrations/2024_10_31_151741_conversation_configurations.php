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
        Schema::create('conversation_configurations', function (Blueprint $table) {
            $table->id();
            $table->string('user_phone')->unique(); // Número de teléfono
            $table->boolean('conversation_enabled')->default(true); // Ejemplo de otra configuración (puedes agregar más)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('conversation_configurations');
    }
};
