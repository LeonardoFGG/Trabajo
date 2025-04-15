

<?php $__env->startSection('content'); ?>
<?php if(session('success')): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>
    <?php echo e(session('success')); ?>

    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<?php if(session('error')): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-circle me-2"></i>
    <?php echo e(session('error')); ?>

    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<div class="container-fluid py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-boxes me-2"></i>Catálogo de Paquetes
                </h5>
                <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#crearPaqueteModal">
                    <i class="fas fa-plus me-2"></i>Nuevo Paquete
                </button>
            </div>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4">ID</th>
                            <th class="px-4">Código</th>
                            <th class="px-4">Nombre</th>
                            <th class="px-4">Precio</th>
                            <th class="px-4">Estado</th>
                            <th class="px-4">Sistema</th>
                            <th class="px-4">Productos</th>
                            <th class="px-4 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $paquetes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paquete): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="px-4"><?php echo e($paquete->id); ?></td>
                            <td class="px-4 fw-bold"><?php echo e($paquete->codigo); ?></td>
                            <td class="px-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-3">
                                        <i class="fas fa-box"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0"><?php echo e($paquete->nombre); ?></h6>
                                        <small class="text-muted"><?php echo e(Str::limit($paquete->descripcion, 40)); ?></small>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 fw-bold text-success">$<?php echo e(number_format($paquete->precio_base, 2)); ?></td>
                            <td class="px-4">
                                <span class="badge rounded-pill bg-<?php echo e($paquete->activo ? 'success' : 'secondary'); ?>">
                                    <?php echo e($paquete->activo ? 'Activo' : 'Inactivo'); ?>

                                </span>
                            </td>
                            <td class="px-4">
                                <?php if($paquete->sistema): ?>
                                <span class="badge bg-primary bg-opacity-10 text-primary">
                                    <?php echo e($paquete->sistema->nombre); ?>

                                </span>
                                <?php else: ?>
                                <span class="text-muted">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4">
                                <?php if($paquete->productos->count() > 0): ?>
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" 
                                    data-bs-target="#productosModal<?php echo e($paquete->id); ?>">
                                    <i class="fas fa-eye me-1"></i> <?php echo e($paquete->productos->count()); ?>

                                </button>
                                <?php else: ?>
                                <span class="text-muted">Sin productos</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                        data-bs-target="#editarPaqueteModal<?php echo e($paquete->id); ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger btn-eliminar" 
                                        data-id="<?php echo e($paquete->id); ?>" 
                                        data-nombre="<?php echo e($paquete->nombre); ?>">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modales para productos de cada paquete -->
<?php $__currentLoopData = $paquetes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paquete): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="modal fade" id="productosModal<?php echo e($paquete->id); ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-box-open me-2"></i>Productos en: <?php echo e($paquete->nombre); ?>

                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php if($paquete->productos->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Tipo</th>
                                <th>Valor</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $paquete->productos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $producto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($index + 1); ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-2">
                                            <i class="fas fa-cube text-primary"></i>
                                        </div>
                                        <div>
                                            <strong><?php echo e($producto->nombre); ?></strong>
                                            <?php if($producto->codigo): ?>
                                            <div class="text-muted small"><?php echo e($producto->codigo); ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-<?php echo e([
                                        'core' => 'primary',
                                        'modulo' => 'info',
                                        'servicio' => 'success',
                                        'estructura' => 'warning',
                                        'implementacion' => 'secondary'
                                    ][$producto->tipo] ?? 'dark'); ?>">
                                        <?php echo e(ucfirst($producto->tipo)); ?>

                                    </span>
                                </td>
                                <td>
                                    <?php if($producto->valor_producto): ?>
                                    <span class="fw-bold">$<?php echo e(number_format($producto->valor_producto, 2)); ?></span>
                                    <?php else: ?>
                                    <span class="text-muted">Incluido</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?php echo e($producto->activo ? 'success' : 'secondary'); ?>">
                                        <?php echo e($producto->activo ? 'Activo' : 'Inactivo'); ?>

                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h6 class="card-title text-muted mb-1">Total Productos</h6>
                                <h3 class="mb-0"><?php echo e($paquete->productos->count()); ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h6 class="card-title text-muted mb-1">Valor Total</h6>
                                <h3 class="mb-0">$<?php echo e(number_format($paquete->productos->sum('valor_producto'), 2)); ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Este paquete no contiene productos</h5>
                </div>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<!-- Modal Crear Paquete -->
<div class="modal fade" id="crearPaqueteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-plus-circle me-2"></i>Nuevo Paquete
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo e(route('paquetes.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nombre*</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Código*</label>
                            <input type="text" name="codigo" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Precio Base*</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" name="precio_base" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Sistema Principal</label>
                            <select name="sistema_id" class="form-select">
                                <option value="">Seleccionar...</option>
                                <?php $__currentLoopData = $sistemas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sistema): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($sistema->id); ?>"><?php echo e($sistema->nombre); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="activo" id="activo" checked>
                                <label class="form-check-label" for="activo">Paquete Activo</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <hr class="my-2">
                            <h6 class="mb-3">
                                <i class="fas fa-cubes me-2"></i>Productos incluidos
                            </h6>
                            <div class="row g-2">
                                <?php $__currentLoopData = $productos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $producto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                            name="productos[]" value="<?php echo e($producto->id); ?>" 
                                            id="producto<?php echo e($producto->id); ?>">
                                        <label class="form-check-label" for="producto<?php echo e($producto->id); ?>">
                                            <?php echo e($producto->nombre); ?>

                                            <small class="text-muted">(<?php echo e($producto->tipo); ?>)</small>
                                            <?php if($producto->valor_producto): ?>
                                            <span class="badge bg-success bg-opacity-10 text-success ms-2">
                                                $<?php echo e(number_format($producto->valor_producto, 2)); ?>

                                            </span>
                                            <?php endif; ?>
                                        </label>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Guardar Paquete
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Confirmación Eliminar -->
<div class="modal fade" id="confirmarEliminarModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="confirmarEliminarBody">
                ¿Estás seguro de eliminar este paquete?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmarEliminarBtn">Eliminar</button>
            </div>
        </div>
    </div>
</div>


<?php $__currentLoopData = $paquetes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paquete): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<!-- Modal Editar Paquete -->
<div class="modal fade" id="editarPaqueteModal<?php echo e($paquete->id); ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>Editar Paquete
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo e(route('paquetes.update', $paquete->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nombre*</label>
                            <input type="text" name="nombre" class="form-control" value="<?php echo e($paquete->nombre); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Código*</label>
                            <input type="text" name="codigo" class="form-control" value="<?php echo e($paquete->codigo); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Precio Base*</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" name="precio_base" class="form-control" 
                                    value="<?php echo e($paquete->precio_base); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Sistema Principal</label>
                            <select name="sistema_id" class="form-select">
                                <option value="">Seleccionar...</option>
                                <?php $__currentLoopData = $sistemas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sistema): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($sistema->id); ?>" 
                                    <?php echo e($paquete->sistema_id == $sistema->id ? 'selected' : ''); ?>>
                                    <?php echo e($sistema->nombre); ?>

                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="2"><?php echo e($paquete->descripcion); ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="activo" 
                                    id="activo<?php echo e($paquete->id); ?>" <?php echo e($paquete->activo ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="activo<?php echo e($paquete->id); ?>">Paquete Activo</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <hr class="my-2">
                            <h6 class="mb-3">
                                <i class="fas fa-cubes me-2"></i>Productos incluidos
                            </h6>
                            <div class="row g-2">
                                <?php $__currentLoopData = $productos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $producto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                            name="productos[]" value="<?php echo e($producto->id); ?>" 
                                            id="editProducto<?php echo e($paquete->id); ?>_<?php echo e($producto->id); ?>"
                                            <?php echo e($paquete->productos->contains($producto->id) ? 'checked' : ''); ?>>
                                        <label class="form-check-label" for="editProducto<?php echo e($paquete->id); ?>_<?php echo e($producto->id); ?>">
                                            <?php echo e($producto->nombre); ?>

                                            <small class="text-muted">(<?php echo e($producto->tipo); ?>)</small>
                                            <?php if($producto->valor_producto): ?>
                                            <span class="badge bg-success bg-opacity-10 text-success ms-2">
                                                $<?php echo e(number_format($producto->valor_producto, 2)); ?>

                                            </span>
                                            <?php endif; ?>
                                        </label>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    $(document).ready(function() {
        // Configuración de la eliminación
        $('.btn-eliminar').click(function() {
            const id = $(this).data('id');
            const nombre = $(this).data('nombre');
            
            $('#confirmarEliminarBody').html(`¿Estás seguro de eliminar el paquete <strong>${nombre}</strong>?`);
            
            $('#confirmarEliminarBtn').off('click').on('click', function() {
                $.ajax({
                    url: `/paquetes/${id}`,
                    type: 'DELETE',
                    data: {
                        _token: '<?php echo e(csrf_token()); ?>'
                    },
                    success: function(response) {
                        if(response.success) {
                            location.reload();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        alert('Error: ' + xhr.responseJSON.message);
                    }
                });
            });
            
            $('#confirmarEliminarModal').modal('show');
        });

        // Validación del formulario de creación
        $('#crearPaqueteModal form').submit(function(e) {
            const codigo = $('input[name="codigo"]').val();
            
            // Validar que al menos un producto esté seleccionado
            if($('input[name="productos[]"]:checked').length === 0) {
                e.preventDefault();
                alert('Por favor selecciona al menos un producto para el paquete.');
            }
        });

        // Mostrar/ocultar elementos según necesidad
        $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>
<?php $__env->stopSection(); ?>



<?php $__env->startSection('styles'); ?>
<style>
    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.05);
    }
    .badge {
        font-weight: 500;
    }
    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.productos', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\WebCoop_Jhoa\Documents\repositorio\Mio\Trabajo\resources\views/paquetes/index.blade.php ENDPATH**/ ?>