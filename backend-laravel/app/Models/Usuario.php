<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens;

    protected $table = 'usuarios';

    protected $fillable = [
        'NOMBRES', 'APELLIDOS', 'CORREO', 'DUI', 'TELEFONO', 'DIRECCION', 'PASSWORD', 'ROL'
    ];

    protected $hidden = ['PASSWORD'];

    public function vehiculos()
    {
        return $this->hasMany(Vehiculo::class, 'USUARIO_ID');
    }

    public function getAuthPassword()
    {
        return $this->PASSWORD;
    }
}
