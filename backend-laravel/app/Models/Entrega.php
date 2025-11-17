<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entrega extends Model
{
    protected $table = 'entregas';
    
    protected $fillable = [
        'pedido_id',
        'usuario_id',
        'estado',
        'direccion',
        'ciudad',
        'telefono',
        'latitud',
        'longitud',
        'observaciones',
        'fecha_entrega_realizada',
    ];

    protected $casts = [
        'fecha_entrega_realizada' => 'datetime',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function conductor()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
