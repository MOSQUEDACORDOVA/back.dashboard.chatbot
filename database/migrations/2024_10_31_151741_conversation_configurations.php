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
            $table->string('user_phone')->nullable()->unique(); // Número de teléfono opcional
            $table->string('user_email')->nullable(); // Correo electrónico del usuario
            $table->string('user_card_id')->nullable(); // ID de la tarjeta del usuario
            $table->string('user_name')->nullable(); // Nombre del usuario
            $table->boolean('conversation_enabled')->default(true);
            $table->unsignedBigInteger('id_next_case')->nullable(); // se usa si se necesita un flujo rigido
            $table->string('thread_id')->nullable();
            $table->string('expected_message_type')->nullable(); // Tipo de mensaje esperado (text, interactive, etc.)
            $table->string('source')->nullable(); // Fuente del mensaje (popup, whatsapp, etc.)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('conversation_configurations');
    }
};
