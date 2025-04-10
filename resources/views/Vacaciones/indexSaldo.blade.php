@extends('layouts.vacaciones')

@section('content')
    <div class="container">
        <h1>Saldo de Vacaciones de Todos los Empleados</h1>

        <!-- Mostrar mensajes de éxito o error -->
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Botón para abrir el modal de creación de saldo de vacaciones -->
        <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#crearSaldoModal">
            Crear Saldo de Vacaciones
        </button>

        <!-- Tabla de saldo de vacaciones -->
        <table id="vacacionesTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Empleado</th>
                    <th>Fecha de Ingreso</th>
                    <th>Periodo</th>
                    <th>Desde</th>
                    <th>Hasta</th>
                    <th>Días Tomados</th>
                    <th>Saldo de Vacaciones</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($vacaciones as $vacacion)
                    <tr>
                        <td>{{ $vacacion->empleado->nombre1 . ' ' . $vacacion->empleado->apellido1 }}</td>
                        <td>{{ $vacacion->fecha_ingreso->format('d-m-Y') }}</td>
                        <td>{{ $vacacion->periodo }}</td>
                        <td>{{ $vacacion->desde ? $vacacion->desde->format('d-m-Y') : 'N/A' }}</td>
                        <td>{{ $vacacion->hasta ? $vacacion->hasta->format('d-m-Y') : 'N/A' }}</td>
                        <td>{{ $vacacion->dias_tomados }} días</td>
                        <td>{{ $vacacion->saldo_vacaciones }} días</td>
                        <td>
                            <!-- Botón para editar -->
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#editarSaldoModal{{ $vacacion->id }}">
                                <i class="fas fa-edit"></i> Editar
                            </button>

                            <!-- Botón para eliminar -->
                            <button type="button" class="btn btn-danger btn-sm"
                                onclick="mostrarConfirmacionEliminar({{ $vacacion->id }}, '{{ $vacacion->empleado->nombre1 }} {{ $vacacion->empleado->apellido1 }}')">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>

                            <!-- Formulario de Eliminación (oculto) -->
                            <form action="{{ route('vacaciones.eliminarSaldo', $vacacion->id) }}" method="POST"
                                id="formEliminar{{ $vacacion->id }}" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>

                    <!-- Modal de Edición -->
                    <div class="modal fade" id="editarSaldoModal{{ $vacacion->id }}" tabindex="-1"
                        aria-labelledby="editarSaldoModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Editar Saldo de Vacaciones</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('vacaciones.editarSaldo', $vacacion->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-3">
                                            <label for="empleado_id" class="form-label">Empleado</label>
                                            <select class="form-select" id="empleado_id" name="empleado_id" required>
                                                @foreach ($empleados as $empleado)
                                                    <option value="{{ $empleado->id }}"
                                                        {{ $vacacion->empleado_id == $empleado->id ? 'selected' : '' }}>
                                                        {{ $empleado->nombre1 . ' ' . $empleado->apellido1 }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="fecha_ingreso" class="form-label">Fecha de Ingreso</label>
                                            <input type="date" class="form-control" name="fecha_ingreso"
                                                value="{{ $vacacion->fecha_ingreso->format('Y-m-d') }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="periodo" class="form-label">Periodo</label>
                                            <input type="text" class="form-control" name="periodo"
                                                value="{{ $vacacion->periodo }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="desde" class="form-label">Desde</label>
                                            <input type="date" class="form-control" name="desde"
                                                value="{{ $vacacion->desde ? $vacacion->desde->format('Y-m-d') : '' }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="hasta" class="form-label">Hasta</label>
                                            <input type="date" class="form-control" name="hasta"
                                                value="{{ $vacacion->hasta ? $vacacion->hasta->format('Y-m-d') : '' }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="dias_tomados" class="form-label">Días Tomados</label>
                                            <input type="number" class="form-control" name="dias_tomados"
                                                value="{{ $vacacion->dias_tomados }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="saldo_vacaciones" class="form-label">Saldo de Vacaciones</label>
                                            <input type="number" class="form-control" name="saldo_vacaciones"
                                                value="{{ $vacacion->saldo_vacaciones }}" required>
                                        </div>
                                        <button type="submit" class="btn btn-success">Guardar Cambios</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>

        <!-- Modal para crear el saldo de vacaciones -->
        <div class="modal fade" id="crearSaldoModal" tabindex="-1" aria-labelledby="crearSaldoModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Crear Saldo de Vacaciones</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('vacaciones.crearSaldo') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="empleado_id" class="form-label">Empleado</label>
                                <select class="form-select" id="empleado_id" name="empleado_id" required>
                                    <option value="">Seleccionar Empleado</option>
                                    @foreach ($empleados as $empleado)
                                        <option value="{{ $empleado->id }}">
                                            {{ $empleado->nombre1 . ' ' . $empleado->apellido1 }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="fecha_ingreso" class="form-label">Fecha de Ingreso</label>
                                <input type="date" class="form-control" name="fecha_ingreso" required>
                            </div>
                            <div class="mb-3">
                                <label for="periodo" class="form-label">Periodo</label>
                                <input type="text" class="form-control" name="periodo" required>
                            </div>
                            <div class="mb-3">
                                <label for="desde" class="form-label">Desde</label>
                                <input type="date" class="form-control" name="desde">
                            </div>
                            <div class="mb-3">
                                <label for="hasta" class="form-label">Hasta</label>
                                <input type="date" class="form-control" name="hasta">
                            </div>
                            <div class="mb-3">
                                <label for="dias_tomados" class="form-label">Días Tomados</label>
                                <input type="number" class="form-control" name="dias_tomados" required>
                            </div>
                            <div class="mb-3">
                                <label for="saldo_vacaciones" class="form-label">Saldo de Vacaciones</label>
                                <input type="number" class="form-control" name="saldo_vacaciones" required>
                            </div>
                            <button type="submit" class="btn btn-success">Crear Saldo</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Confirmación para Eliminar -->
        <div class="modal fade" id="confirmarEliminacionModal" tabindex="-1"
            aria-labelledby="confirmarEliminacionModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmar Eliminación</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        ¿Estás seguro de eliminar este Registro?

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-danger" id="confirmarEliminacionBtn">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Inicializar DataTable
            $('#vacacionesTable').DataTable({
                responsive: true,
                pageLength: 25,
                lengthMenu: [5, 10, 25, 50],
                language: {
                    emptyTable: 'No hay registros',
                    search: 'Buscar:',
                    lengthMenu: 'Mostrar _MENU_ registros',
                    info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
                    paginate: {
                        first: 'Primero',
                        last: 'Último',
                        next: 'Siguiente',
                        previous: 'Anterior'
                    },
                },
                order: [
                    [0, 'desc']
                ],
            });

            // Variable global para almacenar el ID del registro a eliminar
            let registroAEliminar = null;

            // Función para mostrar el modal de confirmación de eliminación
            window.mostrarConfirmacionEliminar = function(id, nombreEmpleado) {
                registroAEliminar = id; // Guardar el ID del registro a eliminar
                const modal = new bootstrap.Modal(document.getElementById('confirmarEliminacionModal'));

                // Mostrar el nombre del empleado en el modal
                document.querySelector('.modal-body').innerHTML = `
                    ¿Estás seguro de que deseas eliminar el saldo de vacaciones de <strong>${nombreEmpleado}</strong>? Esta acción no se puede deshacer.
                `;

                // Mostrar el modal
                modal.show();
            };

            // Configurar el botón de confirmar para enviar el formulario
            document.getElementById('confirmarEliminacionBtn').onclick = function() {
                if (registroAEliminar) {
                    document.getElementById(`formEliminar${registroAEliminar}`).submit();
                }
            };
        });
    </script>
@endsection
