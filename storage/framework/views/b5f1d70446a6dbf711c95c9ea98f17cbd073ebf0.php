

<?php $__env->startSection('content'); ?>
    <div class="container mt-4">
        <!-- Mensajes de éxito o error -->
        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h4>Horas No Justificadas del Empleado</h4>
            </div>
            <div class="card-body">
                <!-- Selección de empleado -->
                <form action="<?php echo e(route('permisos.indexHoras')); ?>" method="GET" class="mb-3">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="empleado_id" class="form-label">Seleccionar Empleado:</label>
                            <select name="empleado_id" id="empleado_id" class="form-select" required>
                                <option value="">Seleccionar</option>
                                <?php $__currentLoopData = $empleados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $empleado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($empleado->id); ?>" <?php if($empleado->id == $empleadoId): ?> selected <?php endif; ?>>
                                        <?php echo e($empleado->nombre1); ?> <?php echo e($empleado->apellido1); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Ver Horas No Justificadas</button>
                        </div>
                    </div>
                </form>

                <?php if($empleadoId): ?>
                    <?php
                        $empleadoSeleccionado = $empleados->firstWhere('id', $empleadoId);
                    ?>
                    <h5 class="mt-3">Horas No Justificadas de <?php echo e($empleadoSeleccionado->nombre1); ?> <?php echo e($empleadoSeleccionado->apellido1); ?></h5>

                    <div class="table-responsive">
                        <table id="tablaHorasNoJustificadas" class="table table-striped table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>ID Permiso</th>
                                    <th>Tipo de Permiso</th>
                                    <th>Fecha del permiso</th>
                                    <th>Horas No Justificadas</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $totalHorasNoJustificadas = 0; ?>
                                <?php $__currentLoopData = $permisos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permiso): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($permiso->empleado_id == $empleadoId && !$permiso->justificado && $permiso->estado === 'Aprobado'): ?>
                                        <?php
                                            $horaSalida = \Carbon\Carbon::createFromFormat('H:i:s', $permiso->hora_salida);
                                            $horaRegreso = \Carbon\Carbon::createFromFormat('H:i:s', $permiso->hora_regreso);
                                            $duracion = $horaSalida->diffInMinutes($horaRegreso) / 60;
                                            $totalHorasNoJustificadas += $duracion;
                                        ?>
                                        <tr>
                                            <td><?php echo e($permiso->id); ?></td>
                                            <td><?php echo e($permiso->tipo_permiso); ?></td>
                                            <td><?php echo e($permiso->fecha_salida); ?></td>
                                            <td><?php echo e(number_format($duracion, 2)); ?> horas</td>
                                            <td>
                                                <span class="badge bg-warning text-dark">No Justificado</span>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <h5>Total de horas no justificadas: 
                            <strong>
                                <?php echo e(isset($horasNoJustificadasPorEmpleado[$empleadoId]) ? number_format($horasNoJustificadasPorEmpleado[$empleadoId], 2) : '0.00'); ?>

                            </strong> horas
                        </h5>

                        <h5>Horas No Justificadas Acumuladas (Sobrante actual): 
                            <strong>
                                <?php echo e(isset($sobrantePorEmpleado[$empleadoId]) ? number_format($sobrantePorEmpleado[$empleadoId], 2) : '0.00'); ?>

                            </strong> horas
                        </h5>
                    </div>

                    <?php if(isset($horasNoJustificadasPorEmpleado[$empleadoId]) && $horasNoJustificadasPorEmpleado[$empleadoId] >= 8): ?>
                        <form action="<?php echo e(route('permisos.calcularHoras')); ?>" method="POST" class="mt-3">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="empleado_id" value="<?php echo e($empleadoId); ?>">
                            <button type="submit" class="btn btn-danger w-100">Calcular y Descontar Vacaciones</button>
                        </form>
                    <?php else: ?>
                        <p class="text-muted mt-3">No se necesita descontar vacaciones ya que las horas no justificadas no superan las 8 horas.</p>
                    <?php endif; ?>
                <?php else: ?>
                    <p class="text-muted mt-3">Selecciona un empleado para ver sus horas no justificadas.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Agregar DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#tablaHorasNoJustificadas').DataTable({
                "language": {
                    "lengthMenu": "Mostrar _MENU_ registros por página",
                    "zeroRecords": "No se encontraron registros",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay registros disponibles",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)",
                    "search": "Buscar:",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.permisos', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\WebCoop_Jhoa\Documents\repositorio\Mio\Trabajo\resources\views/Permisos/indexHoras.blade.php ENDPATH**/ ?>