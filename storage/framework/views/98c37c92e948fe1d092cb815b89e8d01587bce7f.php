<!--------------------------------------------------------
Nombre del Proyecto: ERP
Modulo: Matriz de Cumplimientos
Version: 1.0
Desarrollado por: Karol Macas
Fecha de Inicio:
Ultima Modificación:
--------------------------------------------------------->


<?php $__env->startSection('content'); ?>
    <div class="container mt-7">
        <h1 class="text-center mb-8">Matriz de Cumplimientos</h1>

        <?php if(session('success')): ?>
            <div class="alert alert-success" id="success-message">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="alert alert-danger" id="error-message">
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        <?php if(Auth::user()->isAdmin()): ?>
            <!-- Formulario para seleccionar un empleado (Filtrar por cumplimientos) -->
            <form action="<?php echo e(route('matriz_cumplimientos.index')); ?>" method="GET" class="mb-4 p-4 shadow bg-light rounded">
                <div class="form-group">
                    <label for="empleado_id" class="form-label fs-5">Seleccionar Empleado:</label>
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
            </form>
        <?php endif; ?>

        <div class="d-flex justify-content-between mb-3">
            <a href="<?php echo e(route('matriz_cumplimientos.create')); ?>" class="btn btn-primary btn-lg">Añadir Cumplimiento</a>
        </div>

        <div class="table-responsive">
            <table id="cumplimientos-table" class="table table-hover table-bordered w-100 table-sm">
                <thead class="thead-dark text-center">
                    <tr>
                        <th scope="col">Empleado</th>
                        <th scope="col">Parámetro</th>
                        <th scope="col">Puntos</th>
                        <th scope="col">Cargo</th>
                        <th scope="col">Supervisor</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $cumplimientos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cumplimiento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($cumplimiento->empleado->nombre1); ?> <?php echo e($cumplimiento->empleado->apellido1); ?></td>
                            <td><?php echo e($cumplimiento->parametro->nombre); ?></td>

                            <td>
                                <form method="POST"
                                    action="<?php echo e(route('matriz_cumplimientos.updatePuntos', $cumplimiento->id)); ?>">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PUT'); ?>

                                    <div class="form-group d-flex align-items-center">
                                        <input type="number" name="puntos" id="puntos" step="0.5" min="0"
                                            value="<?php echo e(old('puntos', $cumplimiento->puntos)); ?>" class="form-control"
                                            required style="width: 80px;">
                                        <button type="submit" class="btn btn-primary btn-sm ml-2">Actualizar
                                            puntos</button>
                                    </div>

                                </form>
                            </td>


                            <td><?php echo e($cumplimiento->cargo->nombre_cargo); ?></td>
                            <td><?php echo e($cumplimiento->supervisor->nombre_supervisor); ?></td>
                            <td class="text-center">
                                <a href="<?php echo e(route('matriz_cumplimientos.show', $cumplimiento->id)); ?>"
                                    class="btn btn-info btn-sm" title="Ver"><i class="fas fa-eye fa-md"></i></a>

                                <form action="<?php echo e(route('matriz_cumplimientos.destroy', $cumplimiento->id)); ?>"
                                    method="POST" class="d-inline form-delete">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-danger btn-sm btn-delete" title="Eliminar">
                                        <i class="fas fa-trash fa-md"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="mt-3">
            <?php echo e($cumplimientos->links()); ?>

        </div>

        
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        
        <script>
            $(document).ready(function() {
                $('#cumplimientos-table').DataTable({
                    responsive: true,
                    pageLength: 10, // Número de filas por página
                    lengthMenu: [5, 10, 25, 50], // Opciones de paginación
                    language: {
                        search: "Buscar:",
                        lengthMenu: "Mostrar _MENU_ cumplimientos",
                        info: "Mostrando _START_ a _END_ de _TOTAL_ cumplimientos",
                        paginate: {
                            first: "Primera",
                            last: "Última",
                            next: "Siguiente",
                            previous: "Anterior"
                        }
                    },
                    // Ordenar por ID de forma descendente por defecto
                    order: [
                        [0, 'desc']
                    ]
                });

                // SweetAlert for delete confirmation
                $('.form-delete').submit(function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "¡No podrás revertir esto!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, eliminar!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit();
                        }
                    });
                });

                $(document).ready(function() {
                    // Desaparecer las notificaciones después de 3 segundos
                    setTimeout(function() {
                        $('#success-message').fadeOut('slow');
                        $('#error-message').fadeOut('slow');
                    }, 3000); // 3000 milisegundos = 3 segundos
                });
            });
        </script>

    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\WebCoop_Jhoa\Documents\repositorio\Mio\Trabajo\resources\views/Matriz_Cumplimientos/index.blade.php ENDPATH**/ ?>