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
        <div class="subtitle">Generado el: <?php echo e(now()->format('d/m/Y H:i')); ?></div>
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
            <?php $__currentLoopData = $actividades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $actividad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($actividad->id); ?></td>
                <td><?php echo e($actividad->cliente->nombre ?? 'N/A'); ?></td>
                <td><?php echo e($actividad->empleado ? $actividad->empleado->nombre1.' '.$actividad->empleado->apellido1 : 'N/A'); ?></td>
                <td><?php echo e($actividad->producto->nombre ?? 'N/A'); ?></td>
                <td><?php echo e(Str::limit($actividad->descripcion, 50)); ?></td>
                <td><?php echo e($actividad->estado); ?></td>
                <td><?php echo e($actividad->fecha_inicio->format('d/m/Y H:i')); ?></td>
                <td><?php echo e($actividad->fecha_fin ? $actividad->fecha_fin->format('d/m/Y H:i') : 'N/A'); ?></td>
                <td><?php echo e($actividad->tiempo_real_horas ?? 0); ?>h <?php echo e($actividad->tiempo_real_minutos ?? 0); ?>m</td>
                <td><?php echo e($actividad->prioridad); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    
    <div class="footer">
        Total de actividades: <?php echo e($actividades->count()); ?>

    </div>
</body>
</html><?php /**PATH C:\Users\WebCoop_Jhoa\Documents\repositorio\Mio\Trabajo\resources\views/actividades/reporte_servicio_hora.blade.php ENDPATH**/ ?>