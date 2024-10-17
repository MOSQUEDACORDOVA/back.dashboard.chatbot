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
        Schema::create('delivery', function (Blueprint $table) {
            $table->id();
            $table->integer('id_user');
            
            $table->date('date'); // Fecha

            // Sección Entrepot
            $table->string('arrivee'); // Hora de llegada
            $table->string('depart'); // Hora de salida
            $table->string('duree'); // Duración

            // Sección Vehículo
            $table->bigInteger('arrets'); // Número de paradas
            $table->string('plaque'); // Placa de matrícula

            // Detalles del vehículo
            $table->string('camion'); // Camión
            $table->string('chauffeur'); // Conductor
            $table->string('territoire'); // Territorio

            // Sección Kilometrage
            $table->string('initial'); // Inicial
            $table->string('final'); // Final
            $table->string('parcour'); // Recorrido

            // Otros campos
            $table->integer('cycle'); // Ciclo
            $table->string('quar'); // Cuarto (AM, PM)

            // $table->string('code_client');
            // $table->string('num_du_client');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery');
    }
};
