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
        Schema::create('conversation_histories', function (Blueprint $table) {
            $table->id();
            $table->string('user_phone'); // Número de teléfono del usuario
            $table->string('name');
            $table->enum('role', ['user', 'assistant', 'system']); // Identifica si es usuario o ChatGPT
            $table->text('message'); // Mensaje enviado o recibido
            $table->integer('id_next_case')->nullable();
            $table->integer('configuracion_id')->nullable();
            $table->timestamps(); // Tiempos de creación y actualización
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversation_histories');
    }
};
