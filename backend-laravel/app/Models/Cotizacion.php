<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    protected $table = 'cotizaciones';

    protected $fillable = [
        'USUARIO_ID',
        'LLANTA_ID',
        'CANTIDAD',
        'PRECIO_UNITARIO',
        'SUBTOTAL',
        'ESTADO',
        'NOTAS',
    ];

    protected $casts = [
        'PRECIO_UNITARIO' => 'decimal:2',
        'SUBTOTAL' => 'decimal:2',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'USUARIO_ID');
    }

    public function llanta()
    {
        return $this->belongsTo(Llanta::class, 'LLANTA_ID');
    }
}

