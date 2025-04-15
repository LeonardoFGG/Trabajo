

<?php $__env->startSection('content'); ?>
    <div class="container">

        <?php if(session('success')): ?>
            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
        <?php endif; ?>
        <?php if(session('info')): ?>
            <div class="alert alert-info">
                <?php echo e(session('info')); ?>

            </div>
        <?php endif; ?>

        <h1>Listado de Permisos</h1>


        <?php if(Auth::user()->isAdmin() || Auth::user()->isGerenteGeneral() || Auth::user()->isAsistenteGerencial()): ?>
            <!-- Formulario de filtros -->
            <form action="<?php echo e(route('permisos.index')); ?>" method="GET" class="mb-4 p-4 shadow bg-light rounded"
                style="max-width: 1100px; margin: 0 auto;">
                <div class="d-flex flex-column flex-md-row gap-3 align-items-center">

                    <!-- Nuevo selector de fechas por rango -->
                    <div>
                        <label for="daterange">Seleccionar Rango de Fechas:</label>
                        <!-- Input para seleccionar el rango de fechas -->
                        <input type="text" name="daterange" id="daterange" class="form-control"
                            placeholder="Selecciona un rango"
                            value="<?php echo e(request('start_date') && request('end_date') ? request('start_date') . ' to ' . request('end_date') : ''); ?>">
        
                        <!-- Inputs ocultos para enviar las fechas reales -->
                        <input type="hidden" name="start_date" id="start_date" value="<?php echo e(request('start_date')); ?>">
                        <input type="hidden" name="end_date" id="end_date" value="<?php echo e(request('end_date')); ?>">
                    </div>

                    <script>
                        flatpickr("#daterange", {
                            mode: "range",  // Modo de selección de rango
                            dateFormat: "Y-m-d",  // Formato de la fecha
                            defaultDate: [
                                "<?php echo e(request('start_date', now()->format('Y-m-d'))); ?>",  // Fecha inicial por defecto
                                "<?php echo e(request('end_date', now()->format('Y-m-d'))); ?>"     // Fecha final por defecto
                            ],
                            locale: {
                                firstDayOfWeek: 1,
                                weekdays: {
                                    shorthand: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
                                    longhand: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                                },
                                months: {
                                    shorthand: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                                    longhand: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                                },
                            },
                            onChange: function(selectedDates) {
                                // Cuando se seleccionan ambas fechas, actualizamos los campos ocultos
                                if (selectedDates.length === 2) {
                                    document.getElementById('start_date').value = selectedDates[0].toISOString().slice(0, 10);
                                    document.getElementById('end_date').value = selectedDates[1].toISOString().slice(0, 10);
                                }
                            }
                        });
                    </script>

                    <div class="col-md-4">
                        <label for="empleado_id" class="form-label">Seleccionar Empleado:</label>
                        <div class="input-group">
                            <span class="input-group-text bg-primary text-white">
                                <i class="fas fa-user"></i>
                            </span>
                            <select name="empleado_id" id="empleado_id" class="form-select" onchange="this.form.submit()">
                                <option value="">-- Todos los empleados --</option>
                                <?php $__currentLoopData = $empleados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $empleado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($empleado->id); ?>"
                                        <?php echo e(request('empleado_id') == $empleado->id ? 'selected' : ''); ?>>
                                        <?php echo e($empleado->nombre1); ?> <?php echo e($empleado->apellido1); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center align-items-center mt-3 mt-md-0">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Aplicar Filtro
                        </button>
                    </div>
                </div>
            </form>
        <?php endif; ?>


        <!-- Botón para que solo el empleado pueda enviar solicitud -->
        <?php if(Auth()->user()->isEmpleado()): ?>
            <div class="card mb-4 shadow-sm" style="max-width: 1100px; margin: 0 auto;">
                <div class="card-body">
                    <!-- Botón de solicitud de permiso (separado del formulario) -->
                    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="fas fa-plus-circle"></i> Solicitud de Permiso
                    </button>

                    <!-- Formulario de filtrado (independiente) -->
                    <form action="<?php echo e(route('permisos.index')); ?>" method="GET" class="mb-0">
                        <div class="d-flex flex-column flex-md-row gap-3 align-items-center">
                            <!-- Nuevo selector de fechas por rango -->
                    <div>
                        <label for="daterange">Seleccionar Rango de Fechas:</label>
                        <!-- Input para seleccionar el rango de fechas -->
                        <input type="text" name="daterange" id="daterange" class="form-control"
                            placeholder="Selecciona un rango"
                            value="<?php echo e(request('start_date') && request('end_date') ? request('start_date') . ' to ' . request('end_date') : ''); ?>">
        
                        <!-- Inputs ocultos para enviar las fechas reales -->
                        <input type="hidden" name="start_date" id="start_date" value="<?php echo e(request('start_date')); ?>">
                        <input type="hidden" name="end_date" id="end_date" value="<?php echo e(request('end_date')); ?>">
                    </div>

                    <script>
                        flatpickr("#daterange", {
                            mode: "range",  // Modo de selección de rango
                            dateFormat: "Y-m-d",  // Formato de la fecha
                            defaultDate: [
                                "<?php echo e(request('start_date', now()->format('Y-m-d'))); ?>",  // Fecha inicial por defecto
                                "<?php echo e(request('end_date', now()->format('Y-m-d'))); ?>"     // Fecha final por defecto
                            ],
                            locale: {
                                firstDayOfWeek: 1,
                                weekdays: {
                                    shorthand: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
                                    longhand: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                                },
                                months: {
                                    shorthand: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                                    longhand: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                                },
                            },
                            onChange: function(selectedDates) {
                                // Cuando se seleccionan ambas fechas, actualizamos los campos ocultos
                                if (selectedDates.length === 2) {
                                    document.getElementById('start_date').value = selectedDates[0].toISOString().slice(0, 10);
                                    document.getElementById('end_date').value = selectedDates[1].toISOString().slice(0, 10);
                                }
                            }
                        });
                    </script>

                            <div class="mt-3 mt-md-0">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter"></i> Aplicar Filtro
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <table class="table" id="permisosTable" class="table table-hover table-bordered">
            <thead class="thead-dark text-center">
                <tr>
                    <th>ID</th>
                    <th>Empleado</th>
                    <th>Fecha de Salida</th>
                    <th>Hora de Salida</th>
                    <th>Hora de Regreso</th>
                    <th>Duración</th>
                    <th>Tipo de Permiso</th>
                    <th>Anexo</th>
                    <th>Motivo</th>
                    <th>Estado</th>
                    <th>Justicación</th>
                    <th>Aprobado Por</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $permisos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permiso): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($permiso->id); ?></td>
                        <td><?php echo e($permiso->empleado->nombre1 . ' ' . $permiso->empleado->apellido1); ?></td>
                        <td>
                            <?php if($permiso->fecha_salida): ?>
                                <?php echo e(\Carbon\Carbon::parse($permiso->fecha_salida)->format('Y-m-d')); ?>

                            <?php else: ?>
                                No disponible
                            <?php endif; ?>
                        <td>
                            <?php if($permiso->hora_salida && preg_match('/^\d{2}:\d{2}:\d{2}$/', $permiso->hora_salida)): ?>
                                <?php echo e(\Carbon\Carbon::createFromFormat('H:i:s', $permiso->hora_salida)->format('H:i')); ?>

                            <?php else: ?>
                                No disponible
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($permiso->hora_regreso && preg_match('/^\d{2}:\d{2}:\d{2}$/', $permiso->hora_regreso)): ?>
                                <?php echo e(\Carbon\Carbon::createFromFormat('H:i:s', $permiso->hora_regreso)->format('H:i')); ?>

                            <?php else: ?>
                                No disponible
                            <?php endif; ?>
                        </td>


                        <td>
                            <?php if($permiso->duracion && preg_match('/^\d{2}:\d{2}:\d{2}$/', $permiso->duracion)): ?>
                                <?php echo e(\Carbon\Carbon::createFromFormat('H:i:s', $permiso->duracion)->format('H \h i\m')); ?>

                            <?php else: ?>
                                No registrado
                            <?php endif; ?>
                        </td>

                        <td><?php echo e($permiso->tipo_permiso); ?></td>
                        <td>
                            <?php if(Auth::user()->isEmpleado()): ?>
                                <button class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#editAnexoModal<?php echo e($permiso->id); ?>"> <i
                                        class="bi bi-cloud-arrow-up"></i>
                                </button>
                            <?php endif; ?>
                            <?php if($permiso->anexos): ?>
                                <a href="<?php echo e(asset($permiso->anexos)); ?>" target="_blank" class="btn btn-info">
                                    <i class="bi bi-file-earmark-text-fill"></i>
                                </a>
                            <?php else: ?>
                                <a href="#" class="btn btn-danger text-decoration-line-through" role="button"
                                    aria-disabled="true"><i class="bi bi-file-earmark-text-fill"></i>
                                </a>
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($permiso->motivo); ?></td>

                        <td>
                            <?php
                                $estado = strtolower(trim($permiso->estado));
                            ?>
                            <span
                                class="badge <?php if($estado == 'pendiente'): ?> bg-warning
                               <?php elseif($estado == 'aprobado'): ?> bg-success
                               <?php else: ?> bg-danger <?php endif; ?>"
                                style="font-size: 1.1rem;">
                                <?php echo e($permiso->estado); ?>

                            </span>



                            <!-- Formulario para cambiar el estado (Compact Styling) -->
                            <?php if(Auth::user()->isAdmin() ||
                                    Auth::user()->empleado->es_supervisor ||
                                    Auth::user()->isGerenteGeneral() ||
                                    Auth::user()->isAsistenteGerencial()): ?>
                                <form action="<?php echo e(route('permisos.updateEstado', $permiso->id)); ?>" method="POST"
                                    class="formEstado mt-1">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <input type="hidden" name="aprobado_por" value="<?php echo e(Auth::user()->id); ?>">

                                    <div class="input-group input-group-sm">
                                        <select name="estado" id="estado<?php echo e($permiso->id); ?>"
                                            class="form-select form-select-sm"
                                            style="font-size: 0.875rem; padding: 0.25rem 0.5rem;"
                                            onchange="mostrarConfirmacion(this)">
                                            <option value="Pendiente"
                                                <?php echo e($permiso->estado == 'Pendiente' ? 'selected' : ''); ?>>Pendiente
                                            </option>
                                            <option value="Aprobado"
                                                <?php echo e($permiso->estado == 'Aprobado' ? 'selected' : ''); ?>>Aprobado
                                            </option>
                                            <option value="Rechazado"
                                                <?php echo e($permiso->estado == 'Rechazado' ? 'selected' : ''); ?>>Rechazado
                                            </option>
                                        </select>
                                    </div>
                                </form>
                            <?php endif; ?>
                        </td>


                        <td>
                            <?php
                                $justificacion = strtolower(trim($permiso->justificado)); // Cambiado a $permiso->justificado
                            ?>
                            <span class="badge <?php if($justificacion == '1'): ?> bg-success <?php else: ?> bg-danger <?php endif; ?>"
                                style="font-size: 1.2rem;">
                                <?php echo e($permiso->justificado ? 'Justificado' : 'Sin Justificar'); ?>

                            </span>

                            <?php if(Auth::user()->isAdmin() ||
                                    Auth::user()->isSupervisor() ||
                                    Auth::user()->isGerenteGeneral() ||
                                    Auth::user()->isAsistenteGerencial()): ?>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="flexSwitchCheck<?php echo e($permiso->id); ?>"
                                        <?php echo e($permiso->justificado ? 'checked' : ''); ?>

                                        onchange="toggleJustificacion(<?php echo e($permiso->id); ?>)">
                                    <label class="form-check-label"
                                        for="flexSwitchCheck<?php echo e($permiso->id); ?>">Justificado</label>
                                </div>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if($permiso->aprobado_por): ?>
                                <?php echo e($permiso->aprobadoPor->name); ?> <!-- Mostrar el nombre del usuario que aprobó -->
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>


                        <td class="align-middle">

                            <!-- Botón para editar la hora de regreso -->
                            <button class="btn btn-info mb-2" data-bs-toggle="modal"
                                data-bs-target="#editHoraRegresoModal<?php echo e($permiso->id); ?>">
                                <i class="fas fa-edit fa-md"></i> <i class="fas fa-clock"></i> Editar Hora
                            </button>

                            <!-- Otras acciones -->
                            <?php if(Auth::user()->isAdmin() || Auth::user()->isGerenteGeneral() || Auth::user()->isAsistenteGerencial()): ?>
                                <!-- Botón de Editar -->
                                <div class="d-flex flex-column">
                                    <button class="btn btn-warning btn-sm mb-2" data-bs-toggle="modal"
                                        data-bs-target="#editModal<?php echo e($permiso->id); ?>">
                                        <i class="fas fa-edit fa-md"></i> Motivo
                                    </button>

                                    <!-- Botón de Eliminar -->
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="mostrarConfirmacionEliminar(<?php echo e($permiso->id); ?>, '<?php echo e($permiso->empleado->nombre1); ?> <?php echo e($permiso->empleado->apellido1); ?>')">
                                        <i class="fas fa-trash fa-md"></i> Eliminar
                                    </button>

                                    <!-- Formulario de Eliminación (oculto) -->
                                    <form action="<?php echo e(route('permisos.destroy', $permiso->id)); ?>" method="POST"
                                        id="formEliminar<?php echo e($permiso->id); ?>" style="display: none;">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                    </form>
                                </div>
                            <?php endif; ?>

                        </td>


                        <!-- Modal de Edición para Admin y un empleado especifico -->
                        <?php if(Auth::user()->isAdmin() || Auth::user()->isGerenteGeneral() || Auth::user()->isAsistenteGerencial()): ?>
                            <div class="modal fade" id="editModal<?php echo e($permiso->id); ?>" tabindex="-1"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Editar Permiso</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="<?php echo e(route('permisos.update', $permiso->id)); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PATCH'); ?>
                                                <div class="mb-3">
                                                    <label for="motivo" class="form-label">Motivo</label>
                                                    <textarea class="form-control" name="motivo"><?php echo e($permiso->motivo); ?></textarea>
                                                </div>
                                                <button type="submit" class="btn btn-success">Actualizar</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="modal fade" id="editAnexoModal<?php echo e($permiso->id); ?>" tabindex="-1"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Editar Anexo</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="<?php echo e(route('permisos.updateAnexo', $permiso->id)); ?>" method="POST"
                                            enctype="multipart/form-data">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <div class="mb-3">
                                                <label for="anexos" class="form-label">Nuevo Anexo</label>
                                                <input type="file" class="form-control" name="anexos" required>
                                            </div>
                                            <button type="submit" class="btn btn-success">Actualizar Anexo</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="editHoraRegresoModal<?php echo e($permiso->id); ?>" tabindex="-1"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Editar Hora de Regreso</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="<?php echo e(route('permisos.updateHoraRegreso', $permiso->id)); ?>"
                                            method="POST">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <div class="mb-3">
                                                <label for="hora_regreso" class="form-label">Hora de Regreso</label>
                                                <input type="time" class="form-control" name="hora_regreso"
                                                    value="<?php echo e($permiso->hora_regreso && preg_match('/^\d{2}:\d{2}:\d{2}$/', $permiso->hora_regreso) ? \Carbon\Carbon::createFromFormat('H:i:s', $permiso->hora_regreso)->format('H:i') : ''); ?>"
                                                    required>
                                            </div>
                                            <button type="submit" class="btn btn-success">Guardar</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal de Confirmación -->
                        <div class="modal fade" id="confirmarAprobacionModal" tabindex="-1"
                            aria-labelledby="confirmarAprobacionModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="confirmarAprobacionModalLabel">Confirmar
                                            Aprobación
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        ¿Estás seguro de que deseas aprobar este permiso?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancelar</button>
                                        <button type="button" class="btn btn-primary"
                                            id="confirmarAprobacionBtn">Confirmar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal de Confirmación para Eliminar -->
                        <div class="modal fade" id="confirmarEliminacionModal" tabindex="-1"
                            aria-labelledby="confirmarEliminacionModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="confirmarEliminacionModalLabel">Confirmar
                                            Eliminación
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        ¿Estás seguro de que deseas eliminar este permiso?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancelar</button>
                                        <button type="button" class="btn btn-danger"
                                            id="confirmarEliminacionBtn">Eliminar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>



    <!-- Modal de Crear Permiso para Empleado -->
    <?php if(Auth::user()->isEmpleado()): ?>
        <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Nuevo Permiso</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form action="<?php echo e(route('permisos.store')); ?>" method="POST" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <div class="mb-3">
                                <label for="fecha_solicitud" class="form-label">Fecha de Solicitud</label>
                                <input type="date" class="form-control" name="fecha_solicitud"
                                    value="<?php echo e(now()->toDateString()); ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="fecha_salida" class="form-label">Fecha de Salida</label>
                                <input type="date" class="form-control" name="fecha_salida" required>
                            </div>
                            <div class="mb-3">
                                <label for="hora_salida" class="form-label">Hora de Salida</label>
                                <input type="time" class="form-control" name="hora_salida" required>
                            </div>
                            <div class="mb-3">
                                <label for="hora_regreso" class="form-label">Hora de Regreso</label>
                                <input type="time" class="form-control" name="hora_regreso" required>
                            </div>
                            <div class="mb-3">
                                <label for="tipo_permiso" class="form-label">Tipo de Permiso</label>
                                <select class="form-control" name="tipo_permiso" id="tipo_permiso" required>
                                    <option value="Personal">Personal</option>
                                    <option value="Enfermedad">Enfermedad</option>
                                    <option value="Estudio">Estudios</option>
                                    <option value="Defuncion">Defuncion</option>
                                    <option value="Maternidad/Paternidad">Maternidad/Paternidad</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>
                            <div class="mb-3" id="motivo_container" style="display: none;">
                                <label for="motivo" class="form-label">Motivo del Permiso</label>
                                <textarea class="form-control" name="motivo"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="anexos" class="form-label">Anexos</label>
                                <input type="file" class="form-control" name="anexos">
                            </div>
                            <button type="submit" class="btn btn-success">Enviar Solicitud</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script>
        $(document).ready(function() {
            $('#permisosTable').DataTable({
                responsive: true,
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                language: {
                    emptyTable: 'No hay registros',
                    search: 'Buscar:',
                    lengthMenu: 'Mostrar _MENU_ registros',
                    info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
                    paginate: {
                        first: 'Primero',
                        last: 'Último',
                        next: 'Siguiente',
                        previous: 'Anterior'
                    },
                },
                order: [
                    [0, 'desc']
                ],

                dom: 'Bfrtip', // Botones de exportación
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });
        });


        document.addEventListener('DOMContentLoaded', function() {
            // Obtener los elementos de entrada de hora de salida y hora de regreso
            const horaSalidaInput = document.querySelector('input[name="hora_salida"]');
            const horaRegresoInput = document.querySelector('input[name="hora_regreso"]');
            const duracionInput = document.querySelector('input[name="duracion"]');

            // Función para calcular la duración
            // y mostrarla en el campo de duración
            function calcularDuracion() {
                const horaSalida = horaSalidaInput.value;
                const horaRegreso = horaRegresoInput.value;

                if (horaSalida && horaRegreso) {
                    const [horaSalidaH, horaSalidaM] = horaSalida.split(':').map(Number);
                    const [horaRegresoH, horaRegresoM] = horaRegreso.split(':').map(Number);

                    const salida = new Date(0, 0, 0, horaSalidaH, horaSalidaM);
                    const regreso = new Date(0, 0, 0, horaRegresoH, horaRegresoM);

                    if (regreso <= salida) {
                        alert('La hora de regreso debe ser mayor que la hora de salida.');
                        duracionInput.value = '';
                        return;
                    }

                    const diffMs = regreso - salida;
                    const diffH = Math.floor(diffMs / 3600000); // Horas
                    const diffM = Math.floor((diffMs % 3600000) / 60000); // Minutos

                    // Formatear la duración como HH:MM:SS
                    duracionInput.value =
                        `${diffH.toString().padStart(2, '0')}:${diffM.toString().padStart(2, '0')}:00`;
                } else {
                    duracionInput.value = '';
                }
            }

            // Escuchar cambios en los campos de hora de salida y hora de regreso
            horaSalidaInput.addEventListener('change', calcularDuracion);
            horaRegresoInput.addEventListener('change', calcularDuracion);
        });

        document.getElementById('tipo_permiso').addEventListener('change', function() {
            // Mostrar u ocultar el campo de motivo según la selección del tipo de permiso si es "Otro" le pide el motivo y si no lo oculta
            var motivoContainer = document.getElementById('motivo_container');
            motivoContainer.style.display = this.value === 'Otro' ? 'block' : 'none';
        });


        function cambiarFiltro() {
            // Obtener el valor del filtro seleccionado y mostrar/ocultar los campos correspondientes
            const filtro = document.getElementById('filtro').value;
            document.getElementById('filtro-mes').style.display = filtro === 'mes' ? 'block' : 'none';
            document.getElementById('filtro-semana').style.display = filtro === 'semana' ? 'block' : 'none';
        }


        const empleadoSelect = document.getElementById('empleado_id');
        empleadoSelect.addEventListener('change', () => {
            // Mostrar un mensaje o spinner de carga al enviar el formulario
            const loader = document.createElement('div');
            loader.textContent = 'Cargando...';
            loader.style.fontSize = '16px';
            loader.style.color = 'blue';
            loader.style.marginTop = '10px';
            empleadoSelect.parentElement.appendChild(loader);
            empleadoSelect.form.submit();
        });

        function cambiarFiltro() {
            const filtroSeleccionado = document.getElementById('filtro').value;
            const filtroMes = document.getElementById('filtro-mes');
            const filtroSemana = document.getElementById('filtro-semana');

            if (filtroSeleccionado === 'mes') {
                filtroMes.style.display = 'block';
                filtroSemana.style.display = 'none';
            } else if (filtroSeleccionado === 'semana') {
                filtroSemana.style.display = 'block';
                filtroMes.style.display = 'none';
            } else {
                filtroMes.style.display = 'none';
                filtroSemana.style.display = 'none';
            }
        }



        function mostrarDiasSemana() {
            document.getElementById("dias-semana").style.display = "block";
        }


        $(document).ready(function() {
            console.log("jQuery está funcionando correctamente.");
            setTimeout(function() {
                console.log("Desapareciendo notificaciones...");
                $('#success-message').fadeOut('slow');
                $('#error-message').fadeOut('slow');
            }, 3000);
        });

        // Llamada a la función para ajustar el filtro al cargar la página
        document.addEventListener("DOMContentLoaded", function() {
            cambiarFiltro();
        });

        $(document).ready(function() {
            $('#empleado_id').select2({
                placeholder: "Seleccione un empleado",
                allowClear: true
            });

            $('#filtro').select2({
                placeholder: "Seleccione un filtro",
                allowClear: true
            });

            $('#semana').select2({
                placeholder: "Seleccione una semana",
                allowClear: true
            });
        });

        function toggleJustificacion(permisoId) {
            const checkbox = document.getElementById(`flexSwitchCheck${permisoId}`);
            const isJustificado = checkbox.checked;

            fetch(`/permisos/${permisoId}/toggle-justificacion`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        justificado: isJustificado
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Actualizar la interfaz de usuario si es necesario
                        const badge = document.querySelector(`#flexSwitchCheck${permisoId}`).closest('td')
                            .querySelector('.badge');
                        badge.textContent = isJustificado ? 'Justificado' : 'Sin Justificar';
                        badge.classList.toggle('bg-success', isJustificado);
                        badge.classList.toggle('bg-danger', !isJustificado);
                    } else {
                        alert('Error al actualizar la justificación');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });



        }


        // Función para mostrar el modal de confirmación
        function mostrarConfirmacion(select) {
            const estadoSeleccionado = select.value;
            const fila = select.closest('tr');
            const nombreEmpleado = fila.querySelector('td:nth-child(2)').textContent;

            if (estadoSeleccionado === "Aprobado") {
                const modal = new bootstrap.Modal(document.getElementById('confirmarAprobacionModal'));
                modal.show();

                // Mostrar detalles del permiso en el modal
                document.querySelector('.modal-body').innerHTML = `
            ¿Estás seguro de que deseas aprobar el permiso de <strong>${nombreEmpleado}</strong>?
        `;

                const formulario = select.closest('.formEstado');
                document.getElementById('confirmarAprobacionBtn').onclick = function() {
                    formulario.submit();
                    modal.hide();
                };
            } else {
                select.closest('.formEstado').submit();
            }
        }

        // Función para mostrar el modal de confirmación de eliminación
        function mostrarConfirmacionEliminar(permisoId, nombreEmpleado) {
            const modal = new bootstrap.Modal(document.getElementById('confirmarEliminacionModal'));
            modal.show();

            // Mostrar el nombre del empleado en el modal
            document.querySelector('.modal-body').innerHTML = `
            ¿Estás seguro de que deseas eliminar el permiso de <strong>${nombreEmpleado}</strong>?
        `;

            // Configurar el botón de confirmar para enviar el formulario
            document.getElementById('confirmarEliminacionBtn').onclick = function() {
                document.getElementById(`formEliminar${permisoId}`).submit();
            };
        }

        document.addEventListener("DOMContentLoaded", function() {
            // Asegurarte de que el campo de fecha tenga un valor por defecto (hoy)
            const fechaInput = document.getElementById("fecha");
            if (!fechaInput.value) {
                const today = new Date().toISOString().split("T")[0]; // Formato YYYY-MM-DD
                fechaInput.value = today;
            }
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.permisos', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\WebCoop_Jhoa\Documents\repositorio\Mio\Trabajo\resources\views/Permisos/index.blade.php ENDPATH**/ ?>