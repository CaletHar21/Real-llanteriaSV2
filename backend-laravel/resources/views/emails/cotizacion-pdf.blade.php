<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cotización de Llanta</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            background: linear-gradient(135deg, #f5f5f5 0%, #e0e0e0 100%);
        }

        header {
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
            color: #00f2fe;
            padding: 30px;
            border-radius: 8px 8px 0 0;
            text-align: center;
            border-bottom: 3px solid #00f2fe;
        }

        header h1 {
            font-size: 28px;
            margin-bottom: 5px;
        }

        header p {
            font-size: 14px;
            opacity: 0.8;
        }

        .logo-section {
            text-align: center;
            margin-bottom: 20px;
            font-size: 48px;
        }

        .content {
            background: white;
            padding: 30px;
            margin-bottom: 0;
        }

        .section {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e0e0e0;
        }

        .section:last-child {
            border-bottom: none;
        }

        .section-title {
            color: #00f2fe;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 15px;
            letter-spacing: 1px;
        }

        .cliente-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .info-item {
            background: #f9f9f9;
            padding: 12px;
            border-left: 3px solid #00f2fe;
            border-radius: 4px;
        }

        .info-label {
            color: #00f2fe;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .info-value {
            color: #333;
            font-size: 14px;
            font-weight: 500;
        }

        .llanta-details {
            background: linear-gradient(135deg, rgba(0, 242, 254, 0.05) 0%, rgba(0, 242, 254, 0.02) 100%);
            padding: 20px;
            border-radius: 8px;
            border: 2px solid #00f2fe;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr;
            gap: 15px;
        }

        .detail-item {
            text-align: center;
            padding: 10px;
            background: white;
            border-radius: 6px;
            border: 1px solid #00f2fe;
        }

        .detail-label {
            color: #00f2fe;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .detail-value {
            color: #333;
            font-size: 16px;
            font-weight: bold;
        }

        .resumen-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .resumen-table th {
            background: #0a0a0a;
            color: #00f2fe;
            padding: 12px;
            text-align: left;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
        }

        .resumen-table td {
            padding: 12px;
            border-bottom: 1px solid #e0e0e0;
        }

        .resumen-table tr:nth-child(even) {
            background: #f9f9f9;
        }

        .total-row {
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
            color: #00f2fe;
        }

        .total-row td {
            font-weight: bold;
            font-size: 16px;
            border-bottom: 3px solid #00f2fe;
        }

        .subtotal-box {
            background: linear-gradient(135deg, rgba(0, 242, 254, 0.1) 0%, rgba(0, 242, 254, 0.05) 100%);
            border: 2px solid #00f2fe;
            padding: 20px;
            border-radius: 8px;
            text-align: right;
            margin: 20px 0;
        }

        .subtotal-label {
            color: #00f2fe;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .subtotal-value {
            color: #0a0a0a;
            font-size: 28px;
            font-weight: bold;
        }

        .footer {
            background: #0a0a0a;
            color: #00f2fe;
            padding: 20px;
            text-align: center;
            border-radius: 0 0 8px 8px;
            border-top: 3px solid #00f2fe;
        }

        .footer p {
            margin-bottom: 8px;
            font-size: 12px;
        }

        .footer-contact {
            font-size: 11px;
            opacity: 0.8;
            margin-top: 10px;
        }

        .status-badge {
            display: inline-block;
            background: #fbbf24;
            color: #000;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        @media (max-width: 600px) {
            .cliente-info, .detail-grid {
                grid-template-columns: 1fr;
            }

            header h1 {
                font-size: 20px;
            }

            .container {
                padding: 10px;
            }

            .content {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div class="logo-section">🛞</div>
            <h1>COTIZACIÓN DE LLANTA</h1>
            <p>Llantería SV - Cotización #{{ $cotizacion->id }}</p>
        </header>

        <div class="content">
            <!-- DATOS DEL CLIENTE -->
            <div class="section">
                <div class="section-title">📋 Datos del Cliente</div>
                <div class="cliente-info">
                    <div class="info-item">
                        <div class="info-label">Nombre</div>
                        <div class="info-value">{{ $cotizacion->usuario->NOMBRE ?? 'N/A' }} {{ $cotizacion->usuario->APELLIDO ?? '' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Email</div>
                        <div class="info-value">{{ $cotizacion->usuario->email }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Teléfono</div>
                        <div class="info-value">{{ $cotizacion->usuario->TELEFONO ?? 'No registrado' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Fecha de Cotización</div>
                        <div class="info-value">{{ $cotizacion->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
            </div>

            <!-- DETALLE DE LLANTA -->
            <div class="section">
                <div class="section-title">🛞 Detalle de la Llanta</div>
                <div class="llanta-details">
                    <table width="100%" style="margin-bottom: 15px;">
                        <tr>
                            <td style="text-align: center; padding-bottom: 10px;">
                                <strong style="color: #00f2fe; font-size: 24px;">{{ $cotizacion->llanta->MARCA }}</strong>
                            </td>
                            <td style="text-align: right; padding-bottom: 10px;">
                                <span class="status-badge">{{ $cotizacion->ESTADO }}</span>
                            </td>
                        </tr>
                    </table>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <div class="detail-label">Modelo</div>
                            <div class="detail-value">{{ $cotizacion->llanta->MODELO_LLANTA }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Medida Llanta</div>
                            <div class="detail-value">{{ $cotizacion->llanta->MEDIDA_LLANTA }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Medida Rin</div>
                            <div class="detail-value">{{ $cotizacion->llanta->MEDIDA_RIN }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Tipo Vehículo</div>
                            <div class="detail-value">{{ $cotizacion->llanta->TIPO_VEHICULO }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RESUMEN DE COTIZACIÓN -->
            <div class="section">
                <div class="section-title">💰 Resumen de Cotización</div>
                <table class="resumen-table">
                    <thead>
                        <tr>
                            <th>Descripción</th>
                            <th style="text-align: center;">Cantidad</th>
                            <th style="text-align: right;">Precio Unitario</th>
                            <th style="text-align: right;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $cotizacion->llanta->MARCA }} - {{ $cotizacion->llanta->MODELO_LLANTA }}</td>
                            <td style="text-align: center;">{{ $cotizacion->CANTIDAD }}</td>
                            <td style="text-align: right;">${{ number_format($cotizacion->PRECIO_UNITARIO, 2, ',', '.') }}</td>
                            <td style="text-align: right;"><strong>${{ number_format($cotizacion->SUBTOTAL, 2, ',', '.') }}</strong></td>
                        </tr>
                        <tr class="total-row">
                            <td colspan="3" style="text-align: right;">TOTAL A PAGAR:</td>
                            <td style="text-align: right;">${{ number_format($cotizacion->SUBTOTAL, 2, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- NOTAS -->
            @if($cotizacion->NOTAS)
            <div class="section">
                <div class="section-title">📝 Notas del Administrador</div>
                <div style="background: #f9f9f9; padding: 15px; border-left: 3px solid #00f2fe; border-radius: 4px;">
                    {{ $cotizacion->NOTAS }}
                </div>
            </div>
            @endif
        </div>

        <div class="footer">
            <p><strong>Llantería SV</strong></p>
            <p>🌐 www.llanteria-sv.com</p>
            <p>📞 +503 7234-5678</p>
            <div class="footer-contact">
                <p>Este es un documento automático. Por favor no responda a este correo.</p>
                <p>Para más información, contacte directamente al equipo de ventas.</p>
                <p style="margin-top: 10px; color: #00a8b8;">© {{ now()->year }} Llantería SV. Todos los derechos reservados.</p>
            </div>
        </div>
    </div>
</body>
</html>
