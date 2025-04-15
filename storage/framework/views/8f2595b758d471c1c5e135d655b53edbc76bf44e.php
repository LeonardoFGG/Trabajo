

<?php $__env->startSection('content'); ?>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-7">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white text-center">
                        <h1><i class="fa-solid fa-user-tie"></i> Editar Cliente</h1>
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

                        <form action="<?php echo e(route('clientes.update', $cliente->id)); ?>" method="POST"
                            enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>

                            <fieldset class="border p-3 mb-4">
                                <legend class="text-primary"><i class="fa-solid fa-user-tie"></i> Información del Cliente
                                </legend>
                                <div class="row">

                                    <div class="form-group mb-3">
                                        <label for="productos">Selecciona Productos</label>
                                        <div id="productos">
                                            <?php $__currentLoopData = $productos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $producto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="productos[]" id="producto<?php echo e($producto->id); ?>" value="<?php echo e($producto->id); ?>"
                                                        <?php if(in_array($producto->id, $cliente->productos->pluck('id')->toArray())): ?> checked <?php endif; ?>>
                                                    <label class="form-check-label" for="producto<?php echo e($producto->id); ?>">
                                                        <?php echo e($producto->codigo . ' - ' . $producto->nombre); ?>

                                                    </label>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>

                                    <!-- Campo Nombre -->
                                    <div class="form-group col-md-6">
                                        <label for="nombre">Nombre</label>
                                        <input type="text" name="nombre" class="form-control"
                                            value="<?php echo e(old('nombre', $cliente->nombre)); ?>" required>
                                    </div>

                                    <!-- Campo Dirección -->
                                    <div class="form-group col-md-6">
                                        <label for="direccion">Dirección</label>
                                        <input type="text" name="direccion" class="form-control"
                                            value="<?php echo e(old('direccion', $cliente->direccion)); ?>" >
                                    </div>

                                    <!-- Campo Teléfono -->
                                    <div class="form-group col-md-6">
                                        <label for="telefono">Teléfono</label>
                                        <input type="text" name="telefono" class="form-control"
                                            value="<?php echo e(old('telefono', $cliente->telefono)); ?>" >
                                    </div>

                                    <!-- Campo Email -->
                                    <div class="form-group col-md-6">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" class="form-control"
                                            value="<?php echo e(old('email', $cliente->email)); ?>" >
                                    </div>

                                    <!-- Campo Contacto -->
                                    <div class="form-group col-md-6">
                                        <label for="contacto">Contacto</label>
                                        <input type="text" name="contacto" class="form-control"
                                            value="<?php echo e(old('contacto', $cliente->contacto)); ?>">
                                    </div>

                                    <!-- Campo Total Valor Productos -->
                                    <div class="form-group col-md-6">
                                        <label for="total_valor_productos">Total Valor Productos</label>
                                        <input type="number" name="total_valor_productos" class="form-control"
                                            value="<?php echo e(old('total_valor_productos', $cliente->total_valor_productos)); ?>" >
                                    </div>


                                    <!-- Campo Estado -->
                                    <div class="form-group col-md-6">
                                        <label for="estado">Estado</label>
                                        <select name="estado" class="form-control" required>
                                            <option value="ACTIVO"
                                                <?php echo e(old('estado', $cliente->estado) == 'ACTIVO' ? 'selected' : ''); ?>>Activo
                                            </option>
                                            <option value="INACTIVO"
                                                <?php echo e(old('estado', $cliente->estado) == 'INACTIVO' ? 'selected' : ''); ?>>
                                                Inactivo
                                            </option>
                                        </select>
                                    </div>
                                </div>

                            </fieldset>

                            <fieldset class="border p-3 mb-4">
                                <legend class="text-primary"><i class="fa-solid fa-file"></i> Documentos</legend>
                                <div class="row">

                                    <!-- Campo Contrato Implementación -->
                                    <div class="form-group col-md-6">
                                        <label for="contrato_implementacion">Contrato de Implementación</label>
                                        <input type="file" name="contrato_implementacion" class="form-control">
                                        <?php if($cliente->contrato_implementacion): ?>
                                            <p class="mt-1">Archivo actual: <a
                                                    href="<?php echo e(asset('storage/' . $cliente->contrato_implementacion)); ?>"
                                                    target="_blank">Ver Contrato de Implementación</a></p>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Campo Convenio Datos -->
                                    <div class="form-group col-md-6">
                                        <label for="convenio_datos">Convenio de Datos</label>
                                        <input type="file" name="convenio_datos" class="form-control">
                                        <?php if($cliente->convenio_datos): ?>
                                            <p class="mt-1">Archivo actual: <a
                                                    href="<?php echo e(asset('storage/' . $cliente->convenio_datos)); ?>"
                                                    target="_blank">Ver Convenio de Datos</a></p>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Campo Documentos Otros -->
                                    <div class="form-group col-md-6">
                                        <label for="documento_otros">Documentos Otros</label>
                                        <input type="file" name="documento_otros" class="form-control">
                                        <?php if($cliente->documento_otros): ?>
                                            <p class="mt-1">Archivo actual: <a
                                                    href="<?php echo e(asset('storage/' . $cliente->documento_otros)); ?>"
                                                    target="_blank">Ver Documentos Otros</a></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </fieldset>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="submit" class="btn btn-primary">Actualizar Cliente</button>
                                <a href="<?php echo e(route('clientes.index')); ?>" class="btn btn-danger">Cancelar</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\WebCoop_Jhoa\Documents\repositorio\Mio\Trabajo\resources\views/Clientes/edit.blade.php ENDPATH**/ ?>