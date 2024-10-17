<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    protected $table = 'delivery';

    protected $with = ['clients'];

    protected $fillable = [
        'id_user',
        'date', // Fecha
    
        // Sección Entrepot
        'arrivee', // Hora de llegada
        'depart', // Hora de salida
        'duree', // Duración
    
        // Sección Vehículo
        'arrets', // Número de paradas
        'plaque', // Placa de matrícula
    
        // Detalles del vehículo
        'camion', // Camión
        'chauffeur', // Conductor
        'territoire', // Territorio
    
        // Sección Kilometrage
        'initial', // Inicial
        'final', // Final
        'parcour', // Recorrido
    
        // Otros campos
        'cycle', // Ciclo
        'quar', // Cuarto (AM, PM)

        // 'code_client', // Codigo de cliente
        // 'num_du_client', // Numero de cliente
    ];

    public function clients()
    {
        return $this->hasMany(Client::class, 'id_delivery', 'id');
    }

    public function features()
    {
        return $this->hasManyThrough(Feature::class, Client::class, 'id_delivery', 'id_client', 'id', 'id_client');
    }

    // public function clients()
    // {
    //     return $this->hasMany(Client::class, 'id_delivery', 'id');
    // }

    // public function features()
    // {
    //     return $this->hasMany(Feature::class, 'id_delivery', 'id');
    // }
}
