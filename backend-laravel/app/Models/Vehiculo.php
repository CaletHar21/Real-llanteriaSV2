<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    protected $table = 'vehiculos';

    protected $fillable = [
        'USUARIO_ID',
        'MARCA_ID',
        'MODELO_ID',
        'ANIO',
        'MEDIDA_RIN',
        'MEDIDA_LLANTA',
        'MARCA_LLANTA',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'USUARIO_ID');
    }

    public function marca()
    {
        return $this->belongsTo(Marca::class, 'MARCA_ID');
    }

    public function modelo()
    {
        return $this->belongsTo(Modelo::class, 'MODELO_ID');
    }
}
