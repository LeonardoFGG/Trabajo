@extends('layouts.app')

@section('content')
    <div class="container mt-7">
            
            <h1 class="text-center mb-8">Listado de Cargos</h1>
            <!-- Notificacion temporal -->
            @if (session('success'))
                <div id="success-alert" class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif


            <!-- Filtro por Departamento -->
            <form method="GET" action="{{ route('cargos.index') }}" class="mb-4">
                <div class="row align-items-end g-3">
                    <div class="col-md-4">
                        <label for="departamento_id" class="form-label">Filtrar por Departamento:</label>
                        <select name="departamento_id" id="departamento_id" class="form-select" onchange="this.form.submit()">
                            <option value="">-- Todos los departamentos --</option>
                            @foreach ($departamentos as $departamento)
                                <option value="{{ $departamento->id }}" {{ request('departamento_id') == $departamento->id ? 'selected' : '' }}>
                                    {{ $departamento->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
    
            <div class="d-flex justify-content-between mb-3">
                <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#createCargoModal">
                    Crear Nuevo Cargo
                </button>
            </div>
            
            <div class="table-responsive">
                <table id="cargos-table" class="table table-bordered table-striped w-100 table-sm">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Nombre</th>
                            <th scope="col">Descripción</th>
                            <th scope="col">Codigo de Afiliacion</th>
                            <th scope="col">Salario Basico</th>
                            <th scope="col">Departamento</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
    
                    <tbody>
                        @foreach ($cargos as $cargo)
                            <tr>
                                <td>{{ $cargo->nombre_cargo }}</td>
                                <td>{{ $cargo->descripcion }}</td>
                                <td>{{ $cargo->codigo_afiliacion }}</td>
                                <td>{{ $cargo->salario_basico }}</td> 
                                <td>{{ $cargo->departamento->nombre }}</td>                               
                                <td class="text-center">
    
                                    <!--<a href="{{ route('cargos.show', $cargo->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye "></i>
                                    </a>-->
                                    <!-- Botón para abrir el modal de Ver -->
                                    <button type="button" class="btn btn-info btn-sm" title="Ver"
                                            data-bs-toggle="modal" data-bs-target="#modalShowCargos{{ $cargo->id }}">
                                        <i class="fas fa-eye fa-md"></i>
                                    </button>

                                    <!-- Botón que abre el modal de Editar -->
                                    <button type="button" class="btn btn-warning btn-sm" title="Editar"
                                        data-bs-toggle="modal" data-bs-target="#editModal{{ $cargo->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>


                                    <form action="{{ route('cargos.destroy', $cargo->id) }}" method="POST"
                                        class="d-inline form-delete">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm btn-delete" title="Eliminar">
                                            <i class="fas fa-trash fa-md"></i>
                                        </button>
                                    </form>

                                </td>
                            </tr>
                        @endforeach
                        
                    </tbody>
                </table>
            </div>
            <!-- Movemos los modales fuera de la tabla -->
            @foreach ($cargos as $cargo)
                @include('Cargos.modals.showCargo', ['cargo' => $cargo])
                @include('Cargos.modals.editCargo', ['cargo' => $cargo, 'departamentos' => $departamentos])
            @endforeach
        </div>
        @include('Cargos.modals.createCargo', ['departamentos' => $departamentos])
    </div>

        <!-- DataTables y SweetAlert2 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css" />

    <script>
        $(document).ready(function() {
            // Inicializar DataTable
            $('#cargos-table').DataTable({
                responsive: true,
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                language: {
                    search: "Buscar:",
                    lengthMenu: "Mostrar _MENU_ cargos",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ cargos",
                    paginate: {
                        first: "Primera",
                        last: "Última",
                        next: "Siguiente",
                        previous: "Anterior"
                    }
                },
                order: [[0, 'asc']] // Orden por nombre de cargo (1ª columna)
            });

            // SweetAlert para confirmación de eliminación
            /*$('.form-delete').on('submit', function(event) {
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
            });*/

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
    <!-- script para que la notificacion desaparezca despues de 5 min -->
    <script>
        // Espera hasta que el DOM esté completamente cargado
        document.addEventListener('DOMContentLoaded', function () {
            // Verifica si existe el elemento con id 'success-alert'
            var alert = document.getElementById('success-alert');
            if (alert) {
                // Establecer un temporizador de 5 segundos (5000 ms)
                setTimeout(function() {
                    // Desaparece el mensaje
                    alert.style.display = 'none';
                }, 5000); // 5000 milisegundos = 5 segundos
            }
        });
    </script>
    

@endsection
