

<?php $__env->startSection('content'); ?>
    <div class="container mt-4" style="max-width: 900px;">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white text-center">
                        <h2><i class="fas fa-user-plus"></i> Registrar Nuevo Cliente</h2>
                    </div>

                    <div class="card-body">

                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger">
                                <ul>
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form action="<?php echo e(route('clientes.store')); ?>" method="POST" enctype="multipart/form-data" class="p-4">
                            <?php echo csrf_field(); ?>

                            <!-- Campo Producto -->
                            <div class="form-group mb-3">
                                <label for="productos">Selecciona Productos</label>
                                <div id="productos">
                                    <?php $__currentLoopData = $productos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $producto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="productos[]" id="producto<?php echo e($producto->id); ?>" value="<?php echo e($producto->id); ?>">
                                            <label class="form-check-label" for="producto<?php echo e($producto->id); ?>">
                                                <?php echo e($producto->codigo . ' - ' . $producto->nombre); ?>

                                            </label>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>

                            <!-- Campos alineados con etiqueta a la derecha -->
                            <?php
                                $fields = [
                                    'nombre' => 'Nombre',
                                    'direccion' => 'Dirección',
                                    'telefono' => 'Teléfono',
                                    'email' => 'Email',
                                    'contacto' => 'Contacto'
                                ];
                            ?>

                            <?php $__currentLoopData = $fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="form-group row mb-3">
                                    <label for="<?php echo e($name); ?>" class="col-md-4 col-form-label text-md-right">
                                        <strong><?php echo e($label); ?></strong>
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" name="<?php echo e($name); ?>" class="form-control mb-3" value="<?php echo e(old($name)); ?>">
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            <!-- Campo Contrato de Implementación -->
                            <div class="form-group row mb-3">
                                <label for="contrato_implementacion" class="col-md-4 col-form-label text-md-right">
                                    <strong>Contrato de Implementación</strong>
                                </label>
                                <div class="col-md-6">
                                    <input type="file" name="contrato_implementacion" class="form-control">
                                </div>
                            </div>

                            <!-- Campo Convenio de Datos -->
                            <div class="form-group row mb-3">
                                <label for="convenio_datos" class="col-md-4 col-form-label text-md-right">
                                    <strong>Convenio de Datos</strong>
                                </label>
                                <div class="col-md-6">
                                    <input type="file" name="convenio_datos" class="form-control">
                                </div>
                            </div>

                            <!-- Campo Documentos Otros -->
                            <div class="form-group row mb-3">
                                <label for="documento_otros" class="col-md-4 col-form-label text-md-right">
                                    <strong>Documentos Otros</strong>
                                </label>
                                <div class="col-md-6">
                                    <input type="file" name="documento_otros[]" class="form-control" multiple>
                                </div>
                            </div>

                            <!-- Total de los Productos -->
                            <div class="form-group row mb-3">
                                <label for="total_valor_productos" class="col-md-4 col-form-label text-md-right">
                                    <strong>Valor Total de Productos</strong>
                                </label>
                                <div class="col-md-6">
                                    <input type="number" name="total_valor_productos" id="total_valor_productos" class="form-control">
                                </div>
                            </div>

                            <!-- Campo Estado -->
                            <div class="form-group row mb-3">
                                <label for="estado" class="col-md-4 col-form-label text-md-right">
                                    <strong>Estado</strong>
                                </label>
                                <div class="col-md-6">
                                    <select name="estado" class="form-control" required>
                                        <option value="ACTIVO" <?php echo e(old('estado') == 'ACTIVO' ? 'selected' : ''); ?>>Activo</option>
                                        <option value="INACTIVO" <?php echo e(old('estado') == 'INACTIVO' ? 'selected' : ''); ?>>Inactivo</option>
                                    </select>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-primary">Guardar Cliente</button>
                                <a href="<?php echo e(route('clientes.index')); ?>" class="btn btn-danger">Cancelar</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#productos').select2({
                placeholder: "Selecciona productos",
                allowClear: true
            });
        });
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\WebCoop_Jhoa\Documents\repositorio\Mio\Trabajo\resources\views/Clientes/createCliente.blade.php ENDPATH**/ ?>