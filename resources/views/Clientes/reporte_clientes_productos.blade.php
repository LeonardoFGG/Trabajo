<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Reporte Completo de Clientes Activos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 9pt;
            margin: 0;
            padding: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
        }
        .title {
            font-size: 14pt;
            font-weight: bold;
        }
        .subtitle {
            font-size: 10pt;
            color: #555;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background-color: #2c3e50;
            color: white;
            padding: 6px;
            text-align: left;
            font-size: 8pt;
        }
        td {
            padding: 5px;
            border: 1px solid #ddd;
            font-size: 8pt;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .discount {
            background-color: #e8f5e9;
            color: #2e7d32;
        }
        .surcharge {
            background-color: #ffebee;
            color: #c62828;
        }
        .no-discount {
            background-color: #fff3e0;
        }
        .product-active {
            background-color: #e8f5e9;
        }
        .product-inactive {
            background-color: #ffebee;
        }
        .footer {
            margin-top: 15px;
            text-align: right;
            font-size: 8pt;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Reporte Completo de Clientes Activos</div>
        <div class="subtitle">Generado el: {{ now()->format('d/m/Y H:i') }}</div>
    </div>
    
    @if(count($datos) > 0)
    <table>
        <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="15%">Cliente</th>
                <th width="15%">Producto</th>
                <th width="10%" class="text-right">P. Base</th>
                <th width="10%" class="text-right">P. Especial</th>
                <th width="8%" class="text-right">Diferencia</th>
                <th width="5%" class="text-center">%</th>
                <th width="8%" class="text-center">Estado Prod.</th>
                <th width="8%" class="text-center">Categoría</th>
            </tr>
        </thead>
        <tbody>
            @foreach($datos as $item)
            @php
                $diferenciaClass = '';
                if ($item['diferencia'] > 0) {
                    $diferenciaClass = 'discount';
                } elseif ($item['diferencia'] < 0) {
                    $diferenciaClass = 'surcharge';
                } else {
                    $diferenciaClass = 'no-discount';
                }
                
                $productStatusClass = $item['producto_activo'] ? 'product-active' : 'product-inactive';
            @endphp
            <tr>
                <td>{{ $item['cliente_id'] }}</td>
                <td>{{ $item['cliente_nombre'] }}</td>
                <td>{{ $item['producto_nombre'] }}</td>
                <td class="text-right">${{ number_format($item['precio_base'], 2) }}</td>
                <td class="text-right {{ $item['precio_especial'] != $item['precio_base'] ? 'discount' : 'no-discount' }}">
                    {{ $item['precio_especial'] ? '$'.number_format($item['precio_especial'], 2) : '= Base' }}
                </td>
                <td class="text-right {{ $diferenciaClass }}">
                    ${{ number_format($item['diferencia'], 2) }}
                </td>
                <td class="text-center {{ $diferenciaClass }}">
                    {{ number_format($item['porcentaje'], 2) }}%
                </td>
                <td class="text-center {{ $productStatusClass }}">
                    {{ $item['producto_activo'] ? 'ACTIVO' : 'INACTIVO' }}
                </td>
                <td>{{ $item['categoria'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        <strong>Total clientes:</strong> {{ $totalClientes }} | 
        <strong>Total productos:</strong> {{ count($datos) }} |
        <strong>Diferencia total:</strong> 
        <span class="{{ $totalDiferencia >= 0 ? 'discount' : 'surcharge' }}">
            ${{ number_format($totalDiferencia, 2) }}
        </span>
    </div>
    @else
    <div style="text-align: center; margin-top: 20px; color: #666;">
        No se encontraron clientes activos con productos
    </div>
    @endif
</body>
</html>