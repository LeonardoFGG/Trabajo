

<?php $__env->startSection('content'); ?>
    <div class="container mt-7">
        <h1 class="text-center mb-8">Listado de Clientes</h1>

        <?php if(session('success')): ?>
            <div class="alert alert-success">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-between mb-3">
            <a href="<?php echo e(route('clientes.create')); ?>" class="btn btn-primary">Crear Cliente</a>
        </div>

        <div class="d-flex justify-content-end gap-2 mb-3">
            <a href="<?php echo e(route('clientes.exportar.activos', 'excel')); ?>" 
               class="btn btn-success">
               <i class="fas fa-file-excel"></i> Exportar Excel (Clientes Activos)
            </a>
            <a href="<?php echo e(route('clientes.exportar.activos', 'pdf')); ?>" 
               class="btn btn-danger">
               <i class="fas fa-file-pdf"></i> Exportar PDF (Clientes Activos)
            </a>
        </div>

        <div class="table-responsive">
            <table id="clientes-table" class="table table-bordered table-striped">
                <thead class="thead-dark text-center">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Productos</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Dirección</th>
                        <th scope="col">Teléfono</th>
                        <th scope="col">Email</th>
                        <th scope="col">Contacto</th>
                        <th scope="col">Contrato de Implementacion</th>
                        <th scope="col">Convenio de Datos</th>
                        <th scope="col">Documentos Otros</th>
                        <th scope="col">Valor de los Productos</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $clientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($cliente->id); ?></td>
                            <td>
                                <?php $__currentLoopData = $cliente->productos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $producto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span><?php echo e($producto->codigo . ' - ' . $producto->nombre); ?></span>
                                    <?php if(!$loop->last): ?>
                                        ,
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </td>
                            <td><?php echo e($cliente->nombre); ?></td>
                            <td><?php echo e($cliente->direccion); ?></td>
                            <td><?php echo e($cliente->telefono); ?></td>
                            <td><?php echo e($cliente->email); ?></td>
                            <td><?php echo e($cliente->contacto); ?></td>
                            <td>
                                <?php if($cliente->contrato_implementacion): ?>
                                    <a href="<?php echo e(asset('storage/' . $cliente->contrato_implementacion)); ?>"
                                        class="btn btn-info btn-sm" target="_blank">Ver</a>
                                <?php else: ?>
                                    <span class="text-danger">No tiene contrato de implementación</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($cliente->convenio_datos): ?>
                                    <a href="<?php echo e(asset('storage/' . $cliente->convenio_datos)); ?>"
                                        class="btn btn-info btn-sm" target="_blank">Ver</a>
                                <?php else: ?>
                                    <span class="text-danger">No tiene convenio de datos</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($cliente->documento_otros): ?>
                                    <?php
                                        $documentos = json_decode($cliente->documento_otros, true) ?? [];
                                    ?>
                                    <?php $__currentLoopData = $documentos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $documento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <a href="<?php echo e(asset('storage/' . $documento)); ?>" class="btn btn-info btn-sm mb-2"
                                            target="_blank">Ver Documento</a><br>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <span class="text-danger">No tiene documentos otros</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($cliente->total_valor_productos); ?></td>

                            <td><?php echo e($cliente->estado); ?></td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="<?php echo e(route('clientes.show', $cliente->id)); ?>" class="btn btn-info btn-sm"
                                        title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('clientes.edit', $cliente->id)); ?>" class="btn btn-warning btn-sm"
                                        title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="<?php echo e(route('clientes.destroy', $cliente->id)); ?>" method="POST"
                                        class="d-inline form-delete">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-danger btn-sm btn-delete" title="Eliminar">
                                            <i class="fas fa-trash fa-md"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // SweetAlert para confirmación de eliminación
        $(document).ready(function() {
            // Configuración de DataTables
            $('#clientes-table').DataTable({
                responsive: true,
                pageLength: 10, // Número de filas por página
                lengthMenu: [5, 10, 25, 50], // Opciones de paginación
                language: {
                    search: "Buscar:",
                    lengthMenu: "Mostrar _MENU_ clientes",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ clientes",
                    paginate: {
                        first: "Primera",
                        last: "Última",
                        next: "Siguiente",
                        previous: "Anterior"
                    }
                },
                order: [
                    [0, 'desc'] // Ordenar por ID de forma descendente por defecto
                ]
            });
        });

        document.querySelectorAll('.form-delete').forEach(form => {
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Esta acción no se puede deshacer",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                })
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\WebCoop_Jhoa\Documents\repositorio\Mio\Trabajo\resources\views/Clientes/index.blade.php ENDPATH**/ ?>