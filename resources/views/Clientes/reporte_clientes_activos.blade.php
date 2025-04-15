<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Reporte de Clientes Activos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
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
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: left;
            padding: 5px;
            border: 1px solid #ddd;
            font-size: 9pt;
        }
        td {
            padding: 5px;
            border: 1px solid #ddd;
            font-size: 9pt;
            word-wrap: break-word;
        }
        .footer {
            margin-top: 15px;
            text-align: right;
            font-size: 8pt;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Reporte de Clientes Activos</div>
        <div class="subtitle">Generado el: {{ now()->format('d/m/Y H:i') }}</div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="20%">Nombre</th>
                <th width="15%">Contacto</th>
                <th width="15%">Teléfono</th>
                <th width="15%">Email</th>
                <th width="20%">Productos</th>
                <th width="10%">Valor</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clientes as $cliente)
            <tr>
                <td>{{ $cliente->id }}</td>
                <td>{{ $cliente->nombre }}</td>
                <td>{{ $cliente->contacto }}</td>
                <td>{{ $cliente->telefono }}</td>
                <td>{{ $cliente->email }}</td>
                <td>
                    @foreach($cliente->productos as $producto)
                        {{ $producto->codigo . ' - ' . $producto->nombre }}@if(!$loop->last), @endif
                    @endforeach
                </td>
                <td>${{ number_format($cliente->total_valor_productos, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        Total de clientes activos: {{ $clientes->count() }}
    </div>
</body>
</html>