<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsistenciaVial extends Model
{
    protected $table = 'asistencias_viales';
    
    protected $fillable = [
        'usuario_id',
        'marca_vehiculo',
        'modelo_vehiculo',
        'placa',
        'tipo_problema',
        'descripcion',
        'estado',
        'ubicacion',
        'latitud',
        'longitud',
        'mecanico_id',
        'solucion_aplicada',
        'fecha_resolucion',
    ];

    protected $casts = [
        'fecha_resolucion' => 'datetime',
    ];

    public function cliente()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function mecanico()
    {
        return $this->belongsTo(Usuario::class, 'mecanico_id');
    }
}
