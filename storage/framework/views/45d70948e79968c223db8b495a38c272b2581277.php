

<?php $__env->startSection('content'); ?>
    <div class="container mt-4" style="max-width: 700px;">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h1 class="mb-0">Crear Nuevo Producto</h1>
            </div>
            <div class="card-body">
                <?php if($errors->any()): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?php echo e(route('productos.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>

                    <!-- Campo Nombre -->
                    <div class="form-group">
                        <label for="nombre">Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" class="form-control"
                            placeholder="Ingrese un nombre del Producto" value="<?php echo e(old('nombre')); ?>" required>
                    </div>

                    <!-- Campo Descripción -->
                    <div class="form-group">
                        <label for="descripcion">Descripción <span class="text-danger">*</span></label>
                        <textarea name="descripcion" class="form-control" placeholder="Describe la actividad"><?php echo e(old('descripcion')); ?></textarea>
                    </div>

                    <!-- Valor Producto -->
                    <div class="form-group mt-3">
                        <label for="valor_producto">Valor del Producto <span class="text-danger">*</span></label>
                        <input type="number" name="valor_producto" class="form-control"
                            placeholder="Ingrese el valor del Producto" value="<?php echo e(old('valor_producto')); ?>" >
                    </div>


                    <div class="d-flex justify-content-between mt-4">
                        <button type="submit" class="btn btn-primary">Guardar Producto</button>
                        <a href="<?php echo e(route('productos.index')); ?>" class="btn btn-outline-danger">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\WebCoop_Jhoa\Documents\repositorio\Mio\Trabajo\resources\views/Productos/create.blade.php ENDPATH**/ ?>