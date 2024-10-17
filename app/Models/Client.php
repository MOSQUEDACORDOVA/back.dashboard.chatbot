<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $table = 'clients';

    protected $with = ['features'];

    protected $primaryKey = 'id_client'; 

    protected $fillable = [
        'client_code',
        'num_du_client',
        'hr_arrivee',
        'hr_depart',
        'nom_imprime',
        'ref',
        'retour_marchandise',
        'id_delivery'
    ];

    // Relación con delivery
    public function delivery()
    {
        return $this->belongsTo(Delivery::class, 'id_delivery', 'id');
    }

    public function features()
    {
        return $this->hasMany(Feature::class, 'id_client', 'id_client');
    }
}
