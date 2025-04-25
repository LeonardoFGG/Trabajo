@extends('layouts.matriz')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Lista de Parámetros</h4>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createParametroModal">
                    <i class="fas fa-plus me-1"></i> Crear Nuevo Parámetro
                </button>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Filtro por Departamento -->
            <div class="card mb-3">
                <div class="card-body p-3 bg-light">
                    <form method="GET" action="{{ route('parametros.index') }}" class="mb-0">
                        <div class="row align-items-end g-2">
                            <div class="col-md-4">
                                <label for="departamento_id" class="form-label fw-bold">Filtrar por Departamento:</label>
                                <select name="departamento_id" id="departamento_id" class="form-select form-select-sm">
                                    <option value="">-- Todos los departamentos --</option>
                                    @foreach ($departamentos as $departamento)
                                        <option value="{{ $departamento->id }}" {{ request('departamento_id') == $departamento->id ? 'selected' : '' }}>
                                            {{ $departamento->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-filter me-1"></i> Aplicar Filtro
                                </button>
                            </div>
                            <div class="col-auto">
                                <a href="{{ route('parametros.index') }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-undo me-1"></i> Limpiar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table id="parametros-table" class="table table-bordered table-hover table-striped w-100 table-sm">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">#</th>
                            <th width="40%">Nombre del Parámetro</th>
                            <th width="35%">Departamento</th>
                            <th width="20%">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($parametros as $parametro)
                            <tr>
                                <td class="text-center">{{ $parametro->id }}</td>
                                <td>{{ $parametro->nombre }}</td>
                                <td>
                                    @if($parametro->departamento)
                                        <span class="badge bg-light text-dark">{{ $parametro->departamento->nombre }}</span>
                                    @else
                                        <span class="badge bg-secondary">Sin departamento</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-info" title="Ver"
                                                data-bs-toggle="modal" data-bs-target="#modalShowParametro{{ $parametro->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-warning" title="Editar"
                                                data-bs-toggle="modal" data-bs-target="#editParametro{{ $parametro->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('parametros.destroy', $parametro->id) }}" method="POST"
                                            class="d-inline form-delete">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    @foreach ($parametros as $parametro)
        @include('Parametros.modals.showParametro', ['parametro' => $parametro])
        @include('Parametros.modals.editParametro', ['parametro' => $parametro, 'departamentos' => $departamentos])
    @endforeach
    @include('Parametros.modals.createParametro', ['departamentos' => $departamentos])
</div>

<script>
    $(document).ready(function() {
        // Inicializar DataTable con configuración mejorada
        $('#parametros-table').DataTable({
            responsive: true,
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50],
            language: {
                search: '<i class="fas fa-search"></i> Buscar:',
                lengthMenu: "Mostrar _MENU_ parámetros",
                info: "Mostrando _START_ a _END_ de _TOTAL_ parámetros",
                infoEmpty: "No hay parámetros para mostrar",
                infoFiltered: "(filtrado de _MAX_ parámetros totales)",
                zeroRecords: "No se encontraron parámetros coincidentes",
                paginate: {
                    first: '<i class="fas fa-angle-double-left"></i>',
                    last: '<i class="fas fa-angle-double-right"></i>',
                    next: '<i class="fas fa-angle-right"></i>',
                    previous: '<i class="fas fa-angle-left"></i>'
                }
            },
            order: [[0, 'asc']],
            dom: '<"d-flex justify-content-between align-items-center mb-3"fl>rt<"d-flex justify-content-between align-items-center mt-3"ip>',
            "initComplete": function() {
                $('.dataTables_filter input').addClass('form-control form-control-sm');
                $('.dataTables_length select').addClass('form-select form-select-sm');
            }
        });

        // Manejo de confirmación de eliminación
        $(document).on('submit', '.form-delete', function(event) {
            event.preventDefault();
            const form = this;

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
            });
        });
    });
</script>
@endsection