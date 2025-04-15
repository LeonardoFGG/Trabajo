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
        <div class="subtitle">Generado el: <?php echo e(now()->format('d/m/Y H:i')); ?></div>
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
            <?php $__currentLoopData = $clientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($cliente->id); ?></td>
                <td><?php echo e($cliente->nombre); ?></td>
                <td><?php echo e($cliente->contacto); ?></td>
                <td><?php echo e($cliente->telefono); ?></td>
                <td><?php echo e($cliente->email); ?></td>
                <td>
                    <?php $__currentLoopData = $cliente->productos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $producto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo e($producto->codigo . ' - ' . $producto->nombre); ?><?php if(!$loop->last): ?>, <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </td>
                <td>$<?php echo e(number_format($cliente->total_valor_productos, 2)); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    
    <div class="footer">
        Total de clientes activos: <?php echo e($clientes->count()); ?>

    </div>
</body>
</html><?php /**PATH C:\Users\WebCoop_Jhoa\Documents\repositorio\Mio\Trabajo\resources\views/clientes/reporte_clientes_activos.blade.php ENDPATH**/ ?>