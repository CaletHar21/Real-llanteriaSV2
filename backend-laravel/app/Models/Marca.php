<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    protected $table = 'marcas';

    protected $fillable = ['NOMBRE'];

    public function modelos()
    {
        return $this->hasMany(Modelo::class, 'MARCA_ID');
    }

    public function vehiculos()
    {
        return $this->hasMany(Vehiculo::class, 'MARCA_ID');
    }
}
