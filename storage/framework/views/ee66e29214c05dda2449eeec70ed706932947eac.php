

<?php $__env->startSection('content'); ?>
    <div class="container">


        <!-- Mostrar mensajes de éxito o error -->
        <?php if(session('success')): ?>
            <div class="alert alert-success">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="alert alert-danger">
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        <h1>Listado de Solicitud de Vacaciones</h1>
        <!-- Botón para abrir el modal de solicitud de vacaciones -->
        <?php if(auth()->user()->isEmpleado()): ?>
            <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#crearSolicitudModal">
                Solicitar Vacaciones
            </button>
        <?php endif; ?>

        <!-- Filtros por la fecha de Calendario  -->

        <?php if(Auth::user()->isAdmin() || Auth::user()->isGerenteGeneral() || Auth::user()->isAsistenteGerencial()): ?>
            <!-- Formulario de filtros -->
            <form action="<?php echo e(route('vacaciones.index')); ?>" method="GET" class="mb-4 p-4 shadow bg-light rounded"
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
        <!-- Filtros por empleado -->
        <?php if(auth()->user()->isSupervisor() || auth()->user()->isAdmin()): ?>
            <div class="card mb-4 shadow-sm">

                <div class="card-body">
                    <form method="GET" action="<?php echo e(route('vacaciones.index')); ?>" class="row g-3 align-items-end">
                        <!-- Select de Empleados -->
                        <div class="col-md-8">
                            <label for="empleado_id" class="form-label">Seleccionar Empleado:</label>
                            <select name="empleado_id" id="empleado_id" class="form-select">
                                <option value="">-- Todos los empleados --</option>
                                <?php $__currentLoopData = $empleados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $empleado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($empleado->id); ?>"
                                        <?php echo e(request('empleado_id') == $empleado->id ? 'selected' : ''); ?>>
                                        <?php echo e($empleado->nombre1); ?> <?php echo e($empleado->apellido1); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <!-- Botón de Filtrar -->
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-filter"></i> Filtrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?>


        <!-- Mostrar el saldo de vacaciones -->
        <?php if(auth()->user()->isEmpleado()): ?>
            <h2>Saldo de Vacaciones</h2>
            <?php if($saldo): ?>
                <div class="card mb-4">
                    <div class="card-body">
                        <p><strong>Saldo actual:</strong> <?php echo e($saldo->saldo_vacaciones); ?> días</p>
                        <p><strong>Días tomados:</strong> <?php echo e($saldo->dias_tomados); ?> días</p>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-warning">
                    No se encontró información de vacaciones para este empleado.
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Tabla de solicitudes de vacaciones -->
        <?php if($solicitudes->isEmpty()): ?>
            <p>No hay solicitudes de vacaciones registradas.</p>
        <?php else: ?>
            <table id="solicitudesTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Empleado</th>
                        <th>Fecha de Solicitud</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th>Días Solicitados</th>
                        <th>Estado</th>
                        <th>Aprobado por</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $solicitudes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $solicitud): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($solicitud->empleado->nombre1 . ' ' . $solicitud->empleado->apellido1); ?></td>
                            <td><?php echo e($solicitud->fecha_solicitud); ?></td>
                            <td><?php echo e($solicitud->fecha_inicio); ?></td>
                            <td><?php echo e($solicitud->fecha_fin); ?></td>
                            <td><?php echo e($solicitud->dias_solicitados); ?></td>
                            <td>
                                <?php
                                    $estado = strtolower(trim($solicitud->estado));
                                ?>
                                <!-- Estado Badge -->
                                <span
                                    class="badge <?php if($estado == 'pendiente'): ?> bg-warning
                                                   <?php elseif($estado == 'aprobado'): ?> bg-success
                                                   <?php else: ?> bg-danger <?php endif; ?>"
                                    style="font-size: 1.1rem;">
                                    <?php echo e($solicitud->estado); ?>

                                </span>

                                <!-- Add spacing between badge and form -->
                                <div class="mt-3">
                                    <?php if(Auth::user()->isAdmin() || Auth::user()->empleado->es_supervisor || Auth::user()->empleado->id == 3): ?>
                                        <form action="<?php echo e(route('vacaciones.updateEstado', $solicitud->id)); ?>"
                                            method="POST" class="formEstado">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <input type="hidden" name="aprobado_por" value="<?php echo e(Auth::user()->id); ?>">
                                            <!-- Dropdown for Estado -->
                                            <select name="estado" class="form-select form-select-sm mt-2"
                                                onchange="mostrarConfirmacion(this)">
                                                <option value="">Selecciona</option>
                                                <option value="Pendiente"
                                                    <?php echo e($solicitud->estado == 'Pendiente' ? 'selected' : ''); ?>>Pendiente
                                                </option>
                                                <option value="Aprobado"
                                                    <?php echo e($solicitud->estado == 'Aprobado' ? 'selected' : ''); ?>>Aprobado
                                                </option>
                                                <option value="Rechazado"
                                                    <?php echo e($solicitud->estado == 'Rechazado' ? 'selected' : ''); ?>>Rechazado
                                                </option>
                                            </select>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>

                            <td>
                                <?php if($solicitud->aprobado_por): ?>
                                    <?php echo e($solicitud->aprobadoPor->name); ?>

                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if(Auth::user()->isAdmin() || Auth::user()->id == 3): ?>
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="mostrarConfirmacionEliminar('<?php echo e(route('vacaciones.eliminar', $solicitud->id)); ?>')">
                                        <i class="fas fa-trash fa-md"></i> Eliminar
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php endif; ?>

        <!-- Modal para crear una solicitud de vacaciones -->
        <div class="modal fade" id="crearSolicitudModal" tabindex="-1" aria-labelledby="crearSolicitudModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="crearSolicitudModalLabel">Crear Solicitud de Vacaciones</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <form action="<?php echo e(route('vacaciones.crear')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <div class="mb-3">
                                <label for="fecha_solicitud" class="form-label">Fecha de Solicitud</label>
                                <input type="date" class="form-control" id="fecha_solicitud" name="fecha_solicitud"
                                    value="<?php echo e(\Carbon\Carbon::now()->format('Y-m-d')); ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio"
                                    required onchange="calcularDias()">
                            </div>
                            <div class="mb-3">
                                <label for="fecha_fin" class="form-label">Fecha de Fin</label>
                                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required
                                    onchange="calcularDias()">
                            </div>
                            <div class="mb-3">
                                <label for="dias_solicitados" class="form-label">Días Solicitados</label>
                                <input type="number" class="form-control" id="dias_solicitados" name="dias_solicitados"
                                    readonly>
                            </div>

                            <div class="mb-3">
                                <label for="comentarios" class="form-label">Comentarios</label>
                                <textarea class="form-control" id="comentarios" name="comentarios" rows="3"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Enviar Solicitud</button>
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
                        <h5 class="modal-title" id="confirmarAprobacionModalLabel">Confirmar Aprobación
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        ¿Estás seguro de que deseas aprobar esta Solicitud de Vacación?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="confirmarAprobacionBtn">Confirmar</button>
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
                        <h5 class="modal-title" id="confirmarEliminacionModalLabel">Confirmar Eliminación</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        ¿Estás seguro de que deseas eliminar esta solicitud de vacaciones?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <form id="formEliminarSolicitud" method="POST" style="display:inline;">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Función para calcular los días solicitados entre las fechas de inicio y fin
        function calcularDias() {
            const fechaInicio = document.getElementById('fecha_inicio').value;
            const fechaFin = document.getElementById('fecha_fin').value;

            if (fechaInicio && fechaFin) {
                const startDate = new Date(fechaInicio);
                const endDate = new Date(fechaFin);
                const timeDifference = endDate - startDate; // Diferencia en milisegundos

                if (timeDifference >= 0) {
                    const diasSolicitados = timeDifference / (1000 * 3600 * 24) + 1; // Convertir a días
                    document.getElementById('dias_solicitados').value = diasSolicitados;
                } else {
                    document.getElementById('dias_solicitados').value = 0;
                }
            }
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

        // Función para cambiar el filtro de mes o semana
        function cambiarFiltro() {
            const filtro = document.getElementById('filtro').value;
            const filtroMes = document.getElementById('filtro-mes');
            const filtroSemana = document.getElementById('filtro-semana');

            if (filtro === 'mes') {
                filtroMes.style.display = 'block';
                filtroSemana.style.display = 'none';
            } else {
                filtroMes.style.display = 'none';
                filtroSemana.style.display = 'block';
            }
        }

        function mostrarDiasSemana() {
            document.getElementById("dias-semana").style.display = "block";
        }

        // Inicializar DataTable
        $(document).ready(function() {
            $('#solicitudesTable').DataTable({
                "language": {
                    "lengthMenu": "Mostrar _MENU_ registros por página",
                    "zeroRecords": "No se encontraron registros",
                    "info": "Mostrando la página _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay registros disponibles",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)",
                    "search": "Buscar:",
                    "paginate": {
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                }
            });
        });

        function mostrarConfirmacionEliminar(url) {
            const modal = new bootstrap.Modal(document.getElementById('confirmarEliminacionModal'));
            modal.show();

            // Configurar el formulario de eliminación
            const formEliminar = document.getElementById('formEliminarSolicitud');
            formEliminar.action = url;

            // Limpiar el formulario al cerrar el modal
            document.getElementById('confirmarEliminacionModal').addEventListener('hidden.bs.modal', function() {
                formEliminar.action = '';
            });
        }
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.vacaciones', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\WebCoop_Jhoa\Documents\repositorio\Mio\Trabajo\resources\views/Vacaciones/index.blade.php ENDPATH**/ ?>