<!--------------------------------------------------------
Nombre del Proyecto: ERP
Modulo: Matriz de Cumplimientos
Version: 1.0
Desarrollado por: Karol Macas
Fecha de Inicio:
Ultima Modificación:
--------------------------------------------------------->
@extends('layouts.app')

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

        @if (Auth::user()->isAdmin())
            <!-- Formulario para seleccionar un empleado (Filtrar por cumplimientos) -->
            <form action="{{ route('matriz_cumplimientos.index') }}" method="GET" class="mb-4 p-4 shadow bg-light rounded">
                <div class="form-group">
                    <label for="empleado_id" class="form-label fs-5">Seleccionar Empleado:</label>
                    <div class="input-group">
                        <span class="input-group-text bg-primary text-white">
                            <i class="fas fa-user"></i>
                        </span>
                        <select name="empleado_id" id="empleado_id" class="form-select" onchange="this.form.submit()">
                            <option value="">-- Todos los empleados --</option>
                            @foreach ($empleados as $empleado)
                                <option value="{{ $empleado->id }}"
                                    {{ request('empleado_id') == $empleado->id ? 'selected' : '' }}>
                                    {{ $empleado->nombre1 }} {{ $empleado->apellido1 }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        @endif

        <div class="d-flex justify-content-between mb-3">
            <a href="{{ route('matriz_cumplimientos.create') }}" class="btn btn-primary btn-lg">Añadir Cumplimiento</a>
        </div>

        <div class="table-responsive">
            <table id="cumplimientos-table" class="table table-hover table-bordered w-100 table-sm">
                <thead class="thead-dark text-center">
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
                    @foreach ($cumplimientos as $cumplimiento)
                        <tr>
                            <td>{{ $cumplimiento->empleado->nombre1 }} {{ $cumplimiento->empleado->apellido1 }}</td>
                            <td>{{ $cumplimiento->parametro->nombre }}</td>

                            <td>
                                <form method="POST"
                                    action="{{ route('matriz_cumplimientos.updatePuntos', $cumplimiento->id) }}">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-group d-flex align-items-center">
                                        <input type="number" name="puntos" id="puntos" step="0.5" min="0"
                                            value="{{ old('puntos', $cumplimiento->puntos) }}" class="form-control"
                                            required style="width: 80px;">
                                        <button type="submit" class="btn btn-primary btn-sm ml-2">Actualizar
                                            puntos</button>
                                    </div>

                                </form>
                            </td>


                            <td>{{ $cumplimiento->cargo->nombre_cargo }}</td>
                            <td>{{ $cumplimiento->supervisor->nombre_supervisor }}</td>
                            <td class="text-center">
                                <a href="{{ route('matriz_cumplimientos.show', $cumplimiento->id) }}"
                                    class="btn btn-info btn-sm" title="Ver"><i class="fas fa-eye fa-md"></i></a>

                                <form action="{{ route('matriz_cumplimientos.destroy', $cumplimiento->id) }}"
                                    method="POST" class="d-inline form-delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm btn-delete" title="Eliminar">
                                        <i class="fas fa-trash fa-md"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="mt-3">
            {{ $cumplimientos->links() }}
        </div>

        {{-- SweetAlert script --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        {{-- DataTables initialization --}}
        <script>
            $(document).ready(function() {
                $('#cumplimientos-table').DataTable({
                    responsive: true,
                    pageLength: 10, // Número de filas por página
                    lengthMenu: [5, 10, 25, 50], // Opciones de paginación
                    language: {
                        search: "Buscar:",
                        lengthMenu: "Mostrar _MENU_ cumplimientos",
                        info: "Mostrando _START_ a _END_ de _TOTAL_ cumplimientos",
                        paginate: {
                            first: "Primera",
                            last: "Última",
                            next: "Siguiente",
                            previous: "Anterior"
                        }
                    },
                    // Ordenar por ID de forma descendente por defecto
                    order: [
                        [0, 'desc']
                    ]
                });

                // SweetAlert for delete confirmation
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

                $(document).ready(function() {
                    // Desaparecer las notificaciones después de 3 segundos
                    setTimeout(function() {
                        $('#success-message').fadeOut('slow');
                        $('#error-message').fadeOut('slow');
                    }, 3000); // 3000 milisegundos = 3 segundos
                });
            });
        </script>

    </div>
@endsection
