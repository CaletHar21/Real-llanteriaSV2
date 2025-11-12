@component('mail::message')
# 🛞 Tu Cotización de Llanta

Hola {{ $cotizacion->usuario->NOMBRE ?? 'Cliente' }},

Hemos recibido tu solicitud de cotización. Aquí está el detalle:

## Información de tu Cotización

**Marca:** {{ $cotizacion->llanta->MARCA }}  
**Modelo:** {{ $cotizacion->llanta->MODELO_LLANTA }}  
**Medida Llanta:** {{ $cotizacion->llanta->MEDIDA_LLANTA }}  
**Medida Rin:** {{ $cotizacion->llanta->MEDIDA_RIN }}  
**Tipo de Vehículo:** {{ $cotizacion->llanta->TIPO_VEHICULO }}  

---

## Resumen del Pedido

| Concepto | Cantidad | Precio Unitario | Subtotal |
|----------|----------|-----------------|----------|
| {{ $cotizacion->llanta->MARCA }} - {{ $cotizacion->llanta->MODELO_LLANTA }} | {{ $cotizacion->CANTIDAD }} | ${{ number_format($cotizacion->PRECIO_UNITARIO, 2) }} | **${{ number_format($cotizacion->SUBTOTAL, 2) }}** |

**TOTAL:** **${{ number_format($cotizacion->SUBTOTAL, 2) }}**

---

## ¿Qué sigue?

El equipo de ventas de Llantería SV analizará tu cotización y se contactará contigo pronto por teléfono o correo electrónico para confirmar tu pedido.

**Estado de tu Cotización:** {{ strtoupper($cotizacion->ESTADO) }}

---

### Información Adjunta

Te hemos enviado el PDF con el detalle completo de tu cotización. Por favor, conservalo para futuras referencias.

@component('mail::button', ['url' => 'http://localhost:5173'])
Ver Dashboard
@endcomponent

---

Si tienes preguntas, no dudes en contactarnos:

📞 **Teléfono:** +503 7234-5678  
📧 **Email:** ventas@llanteria-sv.com  
🌐 **Web:** www.llanteria-sv.com

Saludos,  
**El Equipo de Llantería SV**
@endcomponent
