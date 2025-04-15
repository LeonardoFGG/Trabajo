

<?php $__env->startSection('content'); ?>
    <div class="container">
        <?php if(session('success')): ?>
            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Catálogo de Productos</h1>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="fas fa-plus"></i> Nuevo Producto
            </button>
        </div>

        <!-- Filtros -->
        <form action="<?php echo e(route('productos.index')); ?>" method="GET" class="mb-4 p-4 bg-light rounded">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="tipo" class="form-label">Tipo</label>
                    <select name="tipo" id="tipo" class="form-select" onchange="this.form.submit()">
                        <option value="">Todos los tipos</option>
                        <?php $__currentLoopData = ['core', 'modulo', 'servicio', 'estructura', 'proceso','aplicaciones']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($tipo); ?>" <?php echo e(request('tipo') == $tipo ? 'selected' : ''); ?>>
                                <?php echo e(ucfirst($tipo)); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="categoria" class="form-label">Categoría</label>
                    <select name="categoria" id="categoria" class="form-select" onchange="this.form.submit()">
                        <option value="">Todas las categorías</option>
                        <?php $__currentLoopData = $categorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($categoria); ?>" <?php echo e(request('categoria') == $categoria ? 'selected' : ''); ?>>
                                <?php echo e($categoria); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="search" class="form-label">Buscar</label>
                    <div class="input-group">
                        <input type="text" name="search" id="search" class="form-control"
                            placeholder="Nombre, código o descripción" value="<?php echo e(request('search')); ?>">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <a href="<?php echo e(route('productos.index')); ?>" class="btn btn-secondary">
                        <i class="fas fa-sync-alt"></i> Limpiar
                    </a>
                </div>
            </div>
        </form>

        <!-- Tabla de productos -->
        <div class="table-responsive">
            <table class="table table-hover table-bordered" id="productosTable">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Versión</th>
                        <th>Tipo</th>
                        <th>Categoría</th>
                        <th>Valor</th>
                        <th>Periodicidad</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $productos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $producto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($producto->id); ?></td>
                            <td><?php echo e($producto->codigo ?? 'N/A'); ?></td>
                            <td><?php echo e($producto->nombre); ?></td>
                            <td><?php echo e($producto->version ?? 'N/A'); ?></td>
                            <td>
                                <span
                                    class="badge bg-<?php echo e([
                                        'core' => 'primary',
                                        'modulo' => 'info',
                                        'servicio' => 'success',
                                        'estructura' => 'warning',
                                        'proceso' => 'secondary',
                                        'aplicaciones' => 'dark',
                                    ][$producto->tipo] ?? 'dark'); ?>">
                                    <?php echo e(ucfirst($producto->tipo)); ?>

                                </span>
                            </td>
                            <td><?php echo e($producto->categoria ?? 'N/A'); ?></td>
                            <td>
                                <?php if($producto->valor_producto): ?>
                                    $<?php echo e(number_format($producto->valor_producto, 2)); ?>

                                    <?php if($producto->periodicidad_cobro != 'diario'): ?>
                                        /<?php echo e($producto->periodicidad_cobro); ?>

                                    <?php endif; ?>
                                <?php else: ?>
                                    Incluido
                                <?php endif; ?>
                            </td>
                            <td><?php echo e(ucfirst($producto->periodicidad_cobro)); ?></td>
                            <td>
                                <div class="form-check form-switch d-inline-block">
                                    <input class="form-check-input toggle-status" type="checkbox"
                                        data-id="<?php echo e($producto->id); ?>" <?php echo e($producto->activo ? 'checked' : ''); ?>>
                                    <label class="form-check-label">
                                        <?php echo e($producto->activo ? 'Activo' : 'Inactivo'); ?>

                                    </label>
                                </div>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                    data-bs-target="#showModal<?php echo e($producto->id); ?>">
                                    <i class="fas fa-eye"></i>
                                </button>

                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#editModal<?php echo e($producto->id); ?>">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <form action="<?php echo e(route('productos.destroy', $producto->id)); ?>" method="POST"
                                    class="d-inline-block" id="deleteForm<?php echo e($producto->id); ?>">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="button" class="btn btn-sm btn-danger delete-btn"
                                        data-id="<?php echo e($producto->id); ?>" data-bs-toggle="modal"
                                        data-bs-target="#confirmarEliminacionModal">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                <script>
                                    document.querySelector('.delete-btn[data-id="<?php echo e($producto->id); ?>"]').addEventListener('click', function() {
                                        const form = document.getElementById('deleteForm<?php echo e($producto->id); ?>');
                                        const modalBody = document.getElementById('confirmarEliminacionBody');
                                        const confirmBtn = document.getElementById('confirmarEliminacionBtn');

                                        confirmBtn.onclick = function() {
                                            form.submit();
                                        };

                                        modalBody.innerHTML = '¿Estás seguro de que deseas eliminar el producto <strong><?php echo e($producto->nombre); ?></strong>?';
                                    });
                                </script>
                            </td>
                        </tr>

                        <!-- Modal para mostrar detalles -->
                        <div class="modal fade" id="showModal<?php echo e($producto->id); ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title">Detalles del Producto</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <h5><?php echo e($producto->nombre); ?></h5>
                                                <p class="text-muted"><?php echo e($producto->codigo); ?></p>
                                            </div>
                                            <div class="col-md-6 text-end">
                                                <span class="badge bg-<?php echo e($producto->activo ? 'success' : 'danger'); ?>">
                                                    <?php echo e($producto->activo ? 'Activo' : 'Inactivo'); ?>

                                                </span>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Tipo:</strong> <?php echo e(ucfirst($producto->tipo)); ?></p>
                                                <p><strong>Categoría:</strong> <?php echo e($producto->categoria ?? 'N/A'); ?></p>
                                                <p><strong>Versión:</strong> <?php echo e($producto->version ?? 'N/A'); ?></p>
                                                <p><strong>Modalidad:</strong> <?php echo e($producto->modalidad_servicio ?? 'N/A'); ?></p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Valor:</strong>
                                                    <?php if($producto->valor_producto): ?>
                                                        $<?php echo e(number_format($producto->valor_producto, 2)); ?>

                                                        <?php if($producto->periodicidad_cobro != 'diario'): ?>
                                                            /<?php echo e($producto->periodicidad_cobro); ?>

                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        Incluido en paquete
                                                    <?php endif; ?>
                                                </p>
                                                <p><strong>Periodicidad de cobro:</strong>
                                                    <?php echo e(ucfirst($producto->periodicidad_cobro)); ?></p>
                                                <p><strong>Incluido en paquete:</strong>
                                                    <?php echo e($producto->incluido_en_paquete ? 'Sí' : 'No'); ?></p>
                                            </div>
                                        </div>

                                        <hr>

                                        <h6>Descripción</h6>
                                        <p><?php echo e($producto->descripcion ?? 'No hay descripción disponible'); ?></p>

                                        <?php if($producto->productoPadre): ?>
                                            <hr>
                                            <h6>Sistema Principal</h6>
                                            <p><?php echo e($producto->productoPadre->nombre); ?></p>
                                        <?php endif; ?>

                                        <?php if($producto->modulos->count() > 0): ?>
                                            <hr>
                                            <h6>Módulos asociados</h6>
                                            <ul>
                                                <?php $__currentLoopData = $producto->modulos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $modulo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li><?php echo e($modulo->nombre); ?></li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ul>
                                        <?php endif; ?>


                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cerrar</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal para editar -->
                        <div class="modal fade" id="editModal<?php echo e($producto->id); ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-warning text-white">
                                        <h5 class="modal-title">Editar Producto</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="<?php echo e(route('productos.update', $producto->id)); ?>" method="POST">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PUT'); ?>

                                            <div class="mb-3">
                                                <label for="nombre" class="form-label">Nombre</label>
                                                <input type="text" class="form-control" name="nombre"
                                                    value="<?php echo e($producto->nombre); ?>" required>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="version" class="form-label">Versión</label>
                                                    <input type="text" class="form-control" name="version"
                                                        value="<?php echo e($producto->version); ?>">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="codigo" class="form-label">Código</label>
                                                    <input type="text" class="form-control" name="codigo"
                                                        value="<?php echo e($producto->codigo); ?>">
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="tipo" class="form-label">Tipo</label>
                                                    <select class="form-select" name="tipo" required>
                                                        <?php $__currentLoopData = ['core', 'modulo', 'servicio', 'estructura', 'proceso','aplicaciones']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($tipo); ?>"
                                                                <?php echo e($producto->tipo == $tipo ? 'selected' : ''); ?>>
                                                                <?php echo e(ucfirst($tipo)); ?>

                                                            </option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="categoria" class="form-label">Categoría</label>
                                                    <input type="text" class="form-control" name="categoria"
                                                        value="<?php echo e($producto->categoria); ?>">
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="descripcion" class="form-label">Descripción</label>
                                                <textarea class="form-control" name="descripcion" rows="3"><?php echo e($producto->descripcion); ?></textarea>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="valor_producto" class="form-label">Valor</label>
                                                    <input type="number" step="0.01" class="form-control"
                                                        name="valor_producto" value="<?php echo e($producto->valor_producto); ?>">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="periodicidad_cobro" class="form-label">Periodicidad de
                                                        cobro</label>
                                                    <select class="form-select" name="periodicidad_cobro">
                                                        <?php $__currentLoopData = ['diario', 'mensual', 'anual']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $periodicidad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($periodicidad); ?>"
                                                                <?php echo e($producto->periodicidad_cobro == $periodicidad ? 'selected' : ''); ?>>
                                                                <?php echo e(ucfirst($periodicidad)); ?>

                                                            </option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="incluido_en_paquete"
                                                            id="incluido_en_paquete<?php echo e($producto->id); ?>"
                                                            <?php echo e($producto->incluido_en_paquete ? 'checked' : ''); ?>>
                                                        <label class="form-check-label"
                                                            for="incluido_en_paquete<?php echo e($producto->id); ?>">Incluido en
                                                            paquete</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" name="activo"
                                                            id="activo<?php echo e($producto->id); ?>"
                                                            <?php echo e($producto->activo ? 'checked' : ''); ?>>
                                                        <label class="form-check-label"
                                                            for="activo<?php echo e($producto->id); ?>">Activo</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="producto_padre_id" class="form-label">Sistema
                                                    Principal</label>
                                                <select class="form-select" name="producto_padre_id">
                                                    <option value="">-- Ninguno --</option>
                                                    <?php $__currentLoopData = $sistemas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sistema): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($sistema->id); ?>"
                                                            <?php echo e($producto->producto_padre_id == $sistema->id ? 'selected' : ''); ?>>
                                                            <?php echo e($sistema->nombre); ?>

                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="modalidad" class="form-label">Modalidad</label>
                                                <select class="form-select" name="modalidad_servicio">
                                                    <option value="">-- Seleccione --</option>
                                                    <option value="remoto"
                                                        <?php echo e($producto->modalidad_servicio == 'remoto' ? 'selected' : ''); ?>>
                                                        Remoto</option>
                                                    <option value="presencial"
                                                        <?php echo e($producto->modalidad_servicio == 'presencial' ? 'selected' : ''); ?>>
                                                        Presencial</option>
                                                </select>
                                            </div>

                                            <div class="d-grid gap-2">
                                                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        <!-- Modal para crear nuevo producto -->
        <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Nuevo Producto</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="createErrors" class="alert alert-danger d-none"></div>
                        <form id="createProductForm" action="<?php echo e(route('productos.store')); ?>" method="POST">
                            <?php echo csrf_field(); ?>

                            <div class="row">
                                <div class="col-md-6">
                                    <!-- Información Básica -->
                                    <div class="card mb-4">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0">Información Básica</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="nombre" class="form-label">Nombre*</label>
                                                <input type="text" class="form-control" name="nombre" required>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="version" class="form-label">Versión</label>
                                                    <input type="text" class="form-control" name="version"
                                                        placeholder="Ej: 2.0">
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="codigo" class="form-label">Código</label>
                                                    <input type="text" class="form-control" name="codigo"
                                                        placeholder="Ej: BWEB2">
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="descripcion" class="form-label">Descripción</label>
                                                <textarea class="form-control" name="descripcion" rows="3" placeholder="Breve descripción del producto"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <!-- Clasificación -->
                                    <div class="card mb-4">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0">Clasificación</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="tipo" class="form-label">Tipo*</label>
                                                    <select class="form-select" name="tipo" id="tipoSelect" required>
                                                        <option value="">-- Seleccione --</option>
                                                        <option value="core">Core</option>
                                                        <option value="modulo">Módulo</option>
                                                        <option value="servicio">Servicio</option>
                                                        <option value="estructura">Estructura</option>
                                                        <option value="proceso">Proceso</option>
                                                        <option value="aplicaciones">Aplicaciones</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="categoria" class="form-label">Categoría</label>
                                                    <input type="text" class="form-control" name="categoria"
                                                        placeholder="Ej: Banca Electrónica">
                                                </div>
                                            </div>

                                            <!-- Sistema Principal (Solo si el tipo es "módulo") -->
                                            <div class="mb-3" id="parentProductContainer" class="d-none">
                                                <label for="producto_padre_id" class="form-label">Sistema
                                                    Principal</label>
                                                <select class="form-select" name="producto_padre_id"
                                                    id="producto_padre_id">
                                                    <option value="">-- Ninguno --</option>
                                                    <?php $__currentLoopData = $sistemas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sistema): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($sistema->id); ?>">
                                                            <?php echo e($sistema->nombre); ?>

                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>

                                            <!-- Modalidad (Solo si el tipo es "servicio") -->
                                            <div class="mb-3" id="modalidadContainer" class="d-none">
                                                <label for="modalidad" class="form-label">Modalidad</label>
                                                <select class="form-select" name="modalidad_servicio" id="modalidadSelect">
                                                    <option value="">-- Seleccione --</option>
                                                    <option value="remoto">Remoto</option>
                                                    <option value="presencial">Presencial</option>
                                                </select>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <!-- Información Económica -->
                                    <div class="card mb-4">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0">Información Económica</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="valor_producto" class="form-label">Valor</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">$</span>
                                                        <input type="number" step="0.01" class="form-control"
                                                            name="valor_producto" placeholder="0.00">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="periodicidad_cobro"
                                                        class="form-label">Periodicidad*</label>
                                                    <select class="form-select" name="periodicidad_cobro" required>
                                                        <option value="diario">Diario</option>
                                                        <option value="mensual">Mensual</option>
                                                        <option value="anual">Anual</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input" type="checkbox"
                                                    name="incluido_en_paquete" id="incluido_en_paquete">
                                                <label class="form-check-label" for="incluido_en_paquete">Incluido en
                                                    paquete</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <!-- Estado -->
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0">Estado</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input" type="checkbox" name="activo"
                                                    id="activo" checked>
                                                <label class="form-check-label" for="activo">Activo</label>
                                            </div>

                                            <div class="alert alert-info">
                                                <small><i class="fas fa-info-circle"></i> Los campos marcados con * son
                                                    obligatorios.</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid gap-2 mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i> Crear Producto
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de confirmación de eliminación (único) -->
        <div class="modal fade" id="confirmarEliminacionModal" tabindex="-1"
            aria-labelledby="confirmarEliminacionModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmarEliminacionModalLabel">Confirmar Eliminación</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="confirmarEliminacionBody">
                        ¿Estás seguro de que deseas eliminar este producto?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-danger" id="confirmarEliminacionBtn">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
        <script>
            $(document).ready(function() {
               geLength: 10
                }); // Inicializar DataTable
                $('#productosTable').DataTable({
                    responsive: true,
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json'
                    },
                    dom: '<"top"f>rt<"bottom"lip><"clear">',
                    pa

                // Mostrar/ocultar campo de producto padre según el tipo seleccionado
                $('#tipoSelect').change(function() {
                    const tipo = $(this).val();
                    const parentContainer = $('#parentProductContainer');
                    const parentSelect = $('#producto_padre_id');

                    if (tipo === 'modulo' || tipo === 'servicio') {
                        parentContainer.removeClass('d-none');
                        parentSelect.prop('required', true);
                    } else {
                        parentContainer.addClass('d-none');
                        parentSelect.prop('required', false);
                    }
                });

                //Mostrar/ocultar el campo de modalidad según el tipo seleccionado
                $('#tipoSelect').change(function() {
                    const tipo = $(this).val();
                    const modalidadContainer = $('#modalidadContainer');
                    const modalidadSelect = $('#modalidadSelect');

                    if (tipo === 'servicio') {
                        modalidadContainer.removeClass('d-none');
                        modalidadSelect.prop('required', true);
                    } else {
                        modalidadContainer.addClass('d-none');
                        modalidadSelect.prop('required', false);
                    }
                });

                // Manejo de la eliminación de productos
                $(document).on('click', '.btn-eliminar', function(e) {
                    e.preventDefault();
                    var productoId = $(this).data('id');
                    var productoNombre = $(this).data('nombre');

                    // Configurar el modal
                    $('#confirmarEliminacionBody').html(
                        `¿Estás seguro de que deseas eliminar el producto <strong>${productoNombre}</strong>?`
                    );

                    // Configurar el botón de confirmación
                    $('#confirmarEliminacionBtn').off('click').on('click', function() {
                        $(this).html('<i class="fas fa-spinner fa-spin"></i> Eliminando...');
                        $('#formEliminar' + productoId).submit();
                    });

                    // Mostrar el modal
                    $('#confirmarEliminacionModal').modal('show');
                });

                // Alternar estado activo/inactivo
                $('.toggle-status').change(function() {
                    var productId = $(this).data('id');
                    var isActive = $(this).is(':checked');

                    $.ajax({
                        url: '/productos/' + productId + '/toggle-status',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            activo: isActive ? 1 : 0
                        },
                        success: function(data) {
                            if (!data.success) {
                                // Revertir el cambio si hay error
                                $(this).prop('checked', !isActive);
                                alert('Error al actualizar el estado');
                            }
                        }.bind(this),
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                            $(this).prop('checked', !isActive);
                        }.bind(this)
                    });
                });
            });
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.productos', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\WebCoop_Jhoa\Documents\repositorio\Mio\Trabajo\resources\views/Productos/index.blade.php ENDPATH**/ ?>