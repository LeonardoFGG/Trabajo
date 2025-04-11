@extends('layouts.productos')

@section('content')
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-circle me-2"></i>
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

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
                        @foreach($paquetes as $paquete)
                        <tr>
                            <td class="px-4">{{ $paquete->id }}</td>
                            <td class="px-4 fw-bold">{{ $paquete->codigo }}</td>
                            <td class="px-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-3">
                                        <i class="fas fa-box"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $paquete->nombre }}</h6>
                                        <small class="text-muted">{{ Str::limit($paquete->descripcion, 40) }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 fw-bold text-success">${{ number_format($paquete->precio_base, 2) }}</td>
                            <td class="px-4">
                                <span class="badge rounded-pill bg-{{ $paquete->activo ? 'success' : 'secondary' }}">
                                    {{ $paquete->activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td class="px-4">
                                @if($paquete->sistema)
                                <span class="badge bg-primary bg-opacity-10 text-primary">
                                    {{ $paquete->sistema->nombre }}
                                </span>
                                @else
                                <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td class="px-4">
                                @if($paquete->productos->count() > 0)
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" 
                                    data-bs-target="#productosModal{{ $paquete->id }}">
                                    <i class="fas fa-eye me-1"></i> {{ $paquete->productos->count() }}
                                </button>
                                @else
                                <span class="text-muted">Sin productos</span>
                                @endif
                            </td>
                            <td class="px-4 text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                        data-bs-target="#editarPaqueteModal{{ $paquete->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger btn-eliminar" 
                                        data-id="{{ $paquete->id }}" 
                                        data-nombre="{{ $paquete->nombre }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modales para productos de cada paquete -->
@foreach($paquetes as $paquete)
<div class="modal fade" id="productosModal{{ $paquete->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-box-open me-2"></i>Productos en: {{ $paquete->nombre }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if($paquete->productos->count() > 0)
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
                            @foreach($paquete->productos as $index => $producto)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-2">
                                            <i class="fas fa-cube text-primary"></i>
                                        </div>
                                        <div>
                                            <strong>{{ $producto->nombre }}</strong>
                                            @if($producto->codigo)
                                            <div class="text-muted small">{{ $producto->codigo }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ [
                                        'core' => 'primary',
                                        'modulo' => 'info',
                                        'servicio' => 'success',
                                        'estructura' => 'warning',
                                        'implementacion' => 'secondary'
                                    ][$producto->tipo] ?? 'dark' }}">
                                        {{ ucfirst($producto->tipo) }}
                                    </span>
                                </td>
                                <td>
                                    @if($producto->valor_producto)
                                    <span class="fw-bold">${{ number_format($producto->valor_producto, 2) }}</span>
                                    @else
                                    <span class="text-muted">Incluido</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $producto->activo ? 'success' : 'secondary' }}">
                                        {{ $producto->activo ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h6 class="card-title text-muted mb-1">Total Productos</h6>
                                <h3 class="mb-0">{{ $paquete->productos->count() }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h6 class="card-title text-muted mb-1">Valor Total</h6>
                                <h3 class="mb-0">${{ number_format($paquete->productos->sum('valor_producto'), 2) }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Este paquete no contiene productos</h5>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach

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
            <form action="{{ route('paquetes.store') }}" method="POST">
                @csrf
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
                                @foreach($sistemas as $sistema)
                                <option value="{{ $sistema->id }}">{{ $sistema->nombre }}</option>
                                @endforeach
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
                                @foreach($productos as $producto)
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                            name="productos[]" value="{{ $producto->id }}" 
                                            id="producto{{ $producto->id }}">
                                        <label class="form-check-label" for="producto{{ $producto->id }}">
                                            {{ $producto->nombre }}
                                            <small class="text-muted">({{ $producto->tipo }})</small>
                                            @if($producto->valor_producto)
                                            <span class="badge bg-success bg-opacity-10 text-success ms-2">
                                                ${{ number_format($producto->valor_producto, 2) }}
                                            </span>
                                            @endif
                                        </label>
                                    </div>
                                </div>
                                @endforeach
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


@foreach($paquetes as $paquete)
<!-- Modal Editar Paquete -->
<div class="modal fade" id="editarPaqueteModal{{ $paquete->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>Editar Paquete
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('paquetes.update', $paquete->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nombre*</label>
                            <input type="text" name="nombre" class="form-control" value="{{ $paquete->nombre }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Código*</label>
                            <input type="text" name="codigo" class="form-control" value="{{ $paquete->codigo }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Precio Base*</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" name="precio_base" class="form-control" 
                                    value="{{ $paquete->precio_base }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Sistema Principal</label>
                            <select name="sistema_id" class="form-select">
                                <option value="">Seleccionar...</option>
                                @foreach($sistemas as $sistema)
                                <option value="{{ $sistema->id }}" 
                                    {{ $paquete->sistema_id == $sistema->id ? 'selected' : '' }}>
                                    {{ $sistema->nombre }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="2">{{ $paquete->descripcion }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="activo" 
                                    id="activo{{ $paquete->id }}" {{ $paquete->activo ? 'checked' : '' }}>
                                <label class="form-check-label" for="activo{{ $paquete->id }}">Paquete Activo</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <hr class="my-2">
                            <h6 class="mb-3">
                                <i class="fas fa-cubes me-2"></i>Productos incluidos
                            </h6>
                            <div class="row g-2">
                                @foreach($productos as $producto)
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                            name="productos[]" value="{{ $producto->id }}" 
                                            id="editProducto{{ $paquete->id }}_{{ $producto->id }}"
                                            {{ $paquete->productos->contains($producto->id) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="editProducto{{ $paquete->id }}_{{ $producto->id }}">
                                            {{ $producto->nombre }}
                                            <small class="text-muted">({{ $producto->tipo }})</small>
                                            @if($producto->valor_producto)
                                            <span class="badge bg-success bg-opacity-10 text-success ms-2">
                                                ${{ number_format($producto->valor_producto, 2) }}
                                            </span>
                                            @endif
                                        </label>
                                    </div>
                                </div>
                                @endforeach
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
@endforeach

@endsection

@section('scripts')
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
                        _token: '{{ csrf_token() }}'
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
@endsection



@section('styles')
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
@endsection
