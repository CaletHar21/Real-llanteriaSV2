<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Llanta extends Model
{
    protected $table = 'llantas';

    protected $fillable = [
        'MARCA',
        'MODELO_LLANTA',
        'MEDIDA_RIN',
        'MEDIDA_LLANTA',
        'PRECIO',
        'IMAGEN',
        'CONDICION',
        'TIPO_VEHICULO',
        'STOCK',
    ];

    protected $casts = [
        'PRECIO' => 'decimal:2',
        'STOCK' => 'integer',
    ];

    // Asegurar que la URL de la imagen sea absoluta para el frontend (ej: http://localhost:8000/storage/..)
    public function getIMAGENAttribute($value)
    {
        if (empty($value)) {
            return $value;
        }

        // Si el valor ya es una URL completa, devolver tal cual
        if (preg_match('#^https?://#i', $value)) {
            return $value;
        }

        // Si es una ruta tipo /storage/..., convertir a URL absoluta usando la función url()
        if (str_starts_with($value, '/storage/')) {
            return url($value);
        }

        return $value;
    }

    // Relaciones
    public function pedidoItems()
    {
        return $this->hasMany(PedidoItem::class, 'LLANTA_ID');
    }
}
