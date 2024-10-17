<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'features';
    
    // protected $with = ['client'];
    
    // Clave primaria
    protected $primaryKey = 'id_feature';

    // Campos que se pueden llenar masivamente
    protected $fillable = [
        'code_feature',
        'colis',
        'id_client',
        'id_delivery'
    ];

    /**
     * Relación con el modelo Client.
     */

    public function client()
    {
        return $this->belongsTo(Client::class, 'id_client', 'id_client');
    }
    /**
     * Relación con el modelo Delivery.
     */
    public function delivery()
    {
        return $this->belongsTo(Delivery::class, 'id_delivery');
    }
}
