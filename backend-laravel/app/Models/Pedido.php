<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'pedidos';
    
    protected $fillable = [
        'usuario_id',
        'estado',
        'total',
        'notas',
        'fecha_entrega_solicitada',
    ];

    protected $casts = [
        'fecha_entrega_solicitada' => 'datetime',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function detalles()
    {
        return $this->hasMany(DetallePedido::class);
    }

    public function entrega()
    {
        return $this->hasOne(Entrega::class);
    }
}
