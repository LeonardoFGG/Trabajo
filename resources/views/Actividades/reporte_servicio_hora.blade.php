<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Reporte Servicio por Hora</title>
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
            page-break-inside: auto;
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
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Reporte de Servicios por Hora</div>
        <div class="subtitle">Generado el: {{ now()->format('d/m/Y H:i') }}</div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="15%">Cliente</th>
                <th width="15%">Empleado</th>
                <th width="15%">Producto</th>
                <th width="20%">Descripción</th>
                <th width="8%">Estado</th>
                <th width="10%">Inicio</th>
                <th width="10%">Fin</th>
                <th width="7%">Tiempo</th>
                <th width="5%">Prioridad</th>
            </tr>
        </thead>
        <tbody>
            @foreach($actividades as $actividad)
            <tr>
                <td>{{ $actividad->id }}</td>
                <td>{{ $actividad->cliente->nombre ?? 'N/A' }}</td>
                <td>{{ $actividad->empleado ? $actividad->empleado->nombre1.' '.$actividad->empleado->apellido1 : 'N/A' }}</td>
                <td>{{ $actividad->producto->nombre ?? 'N/A' }}</td>
                <td>{{ Str::limit($actividad->descripcion, 50) }}</td>
                <td>{{ $actividad->estado }}</td>
                <td>{{ $actividad->fecha_inicio->format('d/m/Y H:i') }}</td>
                <td>{{ $actividad->fecha_fin ? $actividad->fecha_fin->format('d/m/Y H:i') : 'N/A' }}</td>
                <td>{{ $actividad->tiempo_real_horas ?? 0 }}h {{ $actividad->tiempo_real_minutos ?? 0 }}m</td>
                <td>{{ $actividad->prioridad }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        Total de actividades: {{ $actividades->count() }}
    </div>
</body>
</html>