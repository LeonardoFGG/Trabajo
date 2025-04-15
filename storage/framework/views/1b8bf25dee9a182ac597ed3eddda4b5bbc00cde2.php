

<?php $__env->startSection('content'); ?>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white text-center">
                        <h1><i class="fas fa-info-circle"></i> Detalles de Cliente</h1>
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered table-striped table-hover">
                            <tbody>
                                <tr>
                                    <th><i class="fas fa-hashtag"></i> ID</th>
                                    <td><?php echo e($cliente->id); ?></td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-box"></i> Productos</th>
                                    <td>
                                        <?php if($cliente->productos->isNotEmpty()): ?>
                                            <?php $__currentLoopData = $cliente->productos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $producto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php echo e($producto->codigo . ' - ' . $producto->nombre); ?> <br>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                            <span class="text-danger">No tiene productos asociados</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                    
                                <tr>
                                    <th><i class="fas fa-user"></i> Nombre</th>
                                    <td><?php echo e($cliente->nombre); ?></td>
                                </tr>
                                <tr>
                                    <th><i class="fa-solid fa-location-dot"></i> Dirección</th>
                                    <td><?php echo e($cliente->direccion); ?></td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-phone"></i> Teléfono</th>
                                    <td><?php echo e($cliente->telefono); ?></td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-envelope"></i> Email</th>
                                    <td><?php echo e($cliente->email); ?></td>
                                </tr>
                                <tr>
                                    <th><i class="fa-regular fa-address-book"></i> Contacto</th>
                                    <td><?php echo e($cliente->contacto); ?></td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-file-pdf"></i> Contrato de Implementación</th>
                                    <td>
                                        <?php if($cliente->contrato_implementacion): ?>
                                            <a href="<?php echo e(asset('storage/' . $cliente->contrato_implementacion)); ?>"
                                                class="btn btn-info btn-sm">Ver Contrato de Implementación</a>
                                        <?php else: ?>
                                            <span class="text-danger">No tiene contrato de implementación</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-file-pdf"></i> Convenio de Datos</th>
                                    <td>
                                        <?php if($cliente->convenio_datos): ?>
                                            <a href="<?php echo e(asset('storage/' . $cliente->convenio_datos)); ?>"
                                                class="btn btn-info btn-sm">Ver Convenio de Datos</a>
                                        <?php else: ?>
                                            <span class="text-danger">No tiene convenio de datos</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-file-pdf"></i> Documentos Otros</th>
                                    <td>
                                        <?php if(!empty($urls)): ?>
                                            <?php $__currentLoopData = $urls; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <a href="<?php echo e($url); ?>" class="btn btn-info btn-sm mb-2" target="_blank">Ver Documento</a><br>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                            <span class="text-danger">No tiene documentos otros</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                

                                <tr>
                                    <th><i class="fa-solid fa-credit-card"></i> Precio</th>
                                    <td><?php echo e($cliente->precio); ?></td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-toggle-on"></i> Estado</th>
                                    <td><?php echo e($cliente->estado); ?></td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-calendar-plus"></i> Fecha de Creación</th>
                                    <td><?php echo e($cliente->created_at->format('d/m/Y H:i')); ?></td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-calendar-check"></i> Fecha de Actualización</th>
                                    <td><?php echo e($cliente->updated_at->format('d/m/Y H:i')); ?></td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="mt-4 text-center">
                            <a href="<?php echo e(route('clientes.index')); ?>" class="btn btn-primary">Volver al listado</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\WebCoop_Jhoa\Documents\repositorio\Mio\Trabajo\resources\views/Clientes/show.blade.php ENDPATH**/ ?>