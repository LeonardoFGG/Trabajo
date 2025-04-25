<!--------------------------------------------------------
Nombre del Proyecto: ERP
Modulo: Matriz de Cumplimientos
Version: 1.0
Desarrollado por: Karol Macas
Fecha de Inicio:
Ultima Modificación:
--------------------------------------------------------->
@extends('layouts.matriz')

@section('content')
    <div class="container mt-7">
        <h1 class="text-center mb-8">Matriz de Cumplimientos</h1>

        @if (session('success'))
            <div class="alert alert-success" id="success-message">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger" id="error-message">
                {{ session('error') }}
            </div>
        @endif

        <!-- Filtro de empleados - visible para administradores y gerentes generales -->
        @if (Auth::user()->isAdmin() || Auth::user()->isGerenteGeneral())  
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filtrar por Empleado</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('matriz_cumplimientos.index') }}" method="GET" id="filter-form">
                        <div class="row">
                            <div class="col-md-10">
                                <div class="form-group">
                                    <label for="empleado_id" class="form-label">Seleccionar Empleado:</label>
                                    <select name="empleado_id" id="empleado_id" class="form-select">
                                        <option value="">-- Todos los empleados --</option>
                                        @foreach ($empleados as $empleado)
                                            <option value="{{ $empleado->id }}"
                                                {{ request('empleado_id') == $empleado->id ? 'selected' : '' }}>
                                                {{ $empleado->nombre1 }} {{ $empleado->apellido1 }} {{ $empleado->apellido2 }} 
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search me-1"></i> Filtrar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <div class="d-flex justify-content-between mb-3">
            <a href="{{ route('matriz_cumplimientos.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus-circle me-1"></i> Añadir Cumplimiento
            </a>
            
            @if(request('empleado_id'))
                <a href="{{ route('matriz_cumplimientos.index') }}" class="btn btn-secondary btn-lg">
                    <i class="fas fa-times-circle me-1"></i> Limpiar Filtro
                </a>
            @endif
        </div>

        <div class="card shadow">
            <div class="card-header bg-light">
                <h4 class="mb-0">Lista de Cumplimientos</h4>
                @if(request('empleado_id') && count($cumplimientos) > 0)
                    <p class="text-muted mb-0">
                        Mostrando cumplimientos para: 
                        <strong>{{ $cumplimientos->first()->empleado->nombre1 }} {{ $cumplimientos->first()->empleado->apellido1 }}</strong>
                    </p>
                @endif
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="cumplimientos-table" class="table table-hover table-bordered w-100">
                        <thead class="table-dark text-center">
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
                            @forelse ($cumplimientos as $cumplimiento)
                                <tr>
                                    <td>{{ $cumplimiento->empleado->nombre1 }} {{ $cumplimiento->empleado->apellido1 }}</td>
                                    <td>{{ $cumplimiento->parametro->nombre }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('matriz_cumplimientos.updatePuntos', $cumplimiento->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="input-group">
                                                <input type="number" name="puntos" step="0.5" min="0"
                                                    value="{{ old('puntos', $cumplimiento->puntos) }}" 
                                                    class="form-control form-control-sm" required>
                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-save"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                    <td>{{ $cumplimiento->cargo->nombre_cargo }}</td>
                                    <td>{{ $cumplimiento->supervisor->nombre_supervisor }}</td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('matriz_cumplimientos.show', $cumplimiento->id) }}"
                                                class="btn btn-info btn-sm" title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form action="{{ route('matriz_cumplimientos.destroy', $cumplimiento->id) }}"
                                                method="POST" class="d-inline form-delete">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                               
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    </div>

    {{-- SweetAlert script --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Enviar formulario cuando se cambia el select de empleados
            $('#empleado_id').on('change', function() {
                $('#filter-form').submit();
            });

            // DataTable inicialización
            $('#cumplimientos-table').DataTable({
                responsive: true,
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                infoEmpty: "Mostrando 0 a 0 de 0 registros",
                language: {
                    info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                  
                    search: "Buscar:",
                    lengthMenu: "Mostrar _MENU_ registros",
                    emptyTable: "No hay datos disponibles en la tabla",
                    zeroRecords: "No se encontraron coincidencias"
                   
                    
                    
                },
            
                order: [[0, 'asc']]
            });

            // SweetAlert para confirmación de eliminación
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

            // Desaparecer las notificaciones después de 3 segundos
            setTimeout(function() {
                $('#success-message').fadeOut('slow');
                $('#error-message').fadeOut('slow');
            }, 3000);
        });
    </script>
@endsection