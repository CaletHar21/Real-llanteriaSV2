<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Marca;

class Modelo extends Model
{
    protected $table = 'modelos';

    protected $fillable = [
        'MARCA_ID',
        'MODELO'
    ];

    public function marca()
    {
        return $this->belongsTo(Marca::class, 'MARCA_ID');
    }

    public function getMarcaNombreAttribute()
    {
        return $this->marca ? $this->marca->NOMBRE : null;
    }
}
