<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id('id_client'); // ID del cliente
            $table->string('client_code')->nullable(); // Código del cliente
            $table->string('num_du_client')->nullable(); // Nombre del cliente
            $table->string('hr_arrivee')->nullable(); // Hora de llegada
            $table->string('hr_depart')->nullable(); // Hora de salida
            $table->string('nom_imprime')->nullable(); // Nombre impreso
            $table->string('ref')->nullable(); // Referencia
            $table->string('retour_marchandise')->nullable(); // Retorno de mercadería
            $table->string('id_delivery')->nullable(); 
            $table->timestamps(); // Timestamps de Laravel
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}