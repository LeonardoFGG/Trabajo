@extends('layouts.vacaciones')

@section('content')
    <div class="container">


        <!-- Mostrar mensajes de éxito o error -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <h1>Listado de Solicitud de Vacaciones</h1>
        <!-- Botón para abrir el modal de solicitud de vacaciones -->
        @if (auth()->user()->isEmpleado())
            <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#crearSolicitudModal">
                Solicitar Vacaciones
            </button>
        @endif

        <!-- Filtros por la fecha de Calendario  -->

        @if (Auth::user()->isAdmin() || Auth::user()->isGerenteGeneral() || Auth::user()->isAsistenteGerencial())
            <!-- Formulario de filtros -->
            <form action="{{ route('vacaciones.index') }}" method="GET" class="mb-4 p-4 shadow bg-light rounded"
                style="max-width: 1100px; margin: 0 auto;">
                <div class="d-flex flex-column flex-md-row gap-3 align-items-center">
                    <div>
                        <label for="fecha">Selecciona una Fecha:</label>
                        <!-- Campo tipo date para escoger desde el calendario -->
                        <input type="date" name="fecha" id="fecha" class="form-control"
                            value="{{ request('fecha', now()->toDateString()) }}">
                    </div>

                    <div class="col-md-4">
                        <label for="empleado_id" class="form-label">Seleccionar Empleado:</label>
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

                    <div class="d-flex justify-content-center align-items-center mt-3 mt-md-0">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Aplicar Filtro
                        </button>
                    </div>
                </div>
            </form>
        @endif
        <!-- Filtros por empleado -->
        @if (auth()->user()->isSupervisor() || auth()->user()->isAdmin())
            <div class="card mb-4 shadow-sm">

                <div class="card-body">
                    <form method="GET" action="{{ route('vacaciones.index') }}" class="row g-3 align-items-end">
                        <!-- Select de Empleados -->
                        <div class="col-md-8">
                            <label for="empleado_id" class="form-label">Seleccionar Empleado:</label>
                            <select name="empleado_id" id="empleado_id" class="form-select">
                                <option value="">-- Todos los empleados --</option>
                                @foreach ($empleados as $empleado)
                                    <option value="{{ $empleado->id }}"
                                        {{ request('empleado_id') == $empleado->id ? 'selected' : '' }}>
                                        {{ $empleado->nombre1 }} {{ $empleado->apellido1 }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Botón de Filtrar -->
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-filter"></i> Filtrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif


        <!-- Mostrar el saldo de vacaciones -->
        @if (auth()->user()->isEmpleado())
            <h2>Saldo de Vacaciones</h2>
            @if ($saldo)
                <div class="card mb-4">
                    <div class="card-body">
                        <p><strong>Saldo actual:</strong> {{ $saldo->saldo_vacaciones }} días</p>
                        <p><strong>Días tomados:</strong> {{ $saldo->dias_tomados }} días</p>
                    </div>
                </div>
            @else
                <div class="alert alert-warning">
                    No se encontró información de vacaciones para este empleado.
                </div>
            @endif
        @endif

        <!-- Tabla de solicitudes de vacaciones -->
        @if ($solicitudes->isEmpty())
            <p>No hay solicitudes de vacaciones registradas.</p>
        @else
            <table id="solicitudesTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Empleado</th>
                        <th>Fecha de Solicitud</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th>Días Solicitados</th>
                        <th>Estado</th>
                        <th>Aprobado por</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($solicitudes as $solicitud)
                        <tr>
                            <td>{{ $solicitud->empleado->nombre1 . ' ' . $solicitud->empleado->apellido1 }}</td>
                            <td>{{ $solicitud->fecha_solicitud }}</td>
                            <td>{{ $solicitud->fecha_inicio }}</td>
                            <td>{{ $solicitud->fecha_fin }}</td>
                            <td>{{ $solicitud->dias_solicitados }}</td>
                            <td>
                                @php
                                    $estado = strtolower(trim($solicitud->estado));
                                @endphp
                                <!-- Estado Badge -->
                                <span
                                    class="badge @if ($estado == 'pendiente') bg-warning
                                                   @elseif($estado == 'aprobado') bg-success
                                                   @else bg-danger @endif"
                                    style="font-size: 1.1rem;">
                                    {{ $solicitud->estado }}
                                </span>

                                <!-- Add spacing between badge and form -->
                                <div class="mt-3">
                                    @if (Auth::user()->isAdmin() || Auth::user()->empleado->es_supervisor || Auth::user()->empleado->id == 3)
                                        <form action="{{ route('vacaciones.updateEstado', $solicitud->id) }}"
                                            method="POST" class="formEstado">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="aprobado_por" value="{{ Auth::user()->id }}">
                                            <!-- Dropdown for Estado -->
                                            <select name="estado" class="form-select form-select-sm mt-2"
                                                onchange="mostrarConfirmacion(this)">
                                                <option value="">Selecciona</option>
                                                <option value="Pendiente"
                                                    {{ $solicitud->estado == 'Pendiente' ? 'selected' : '' }}>Pendiente
                                                </option>
                                                <option value="Aprobado"
                                                    {{ $solicitud->estado == 'Aprobado' ? 'selected' : '' }}>Aprobado
                                                </option>
                                                <option value="Rechazado"
                                                    {{ $solicitud->estado == 'Rechazado' ? 'selected' : '' }}>Rechazado
                                                </option>
                                            </select>
                                        </form>
                                    @endif
                                </div>
                            </td>

                            <td>
                                @if ($solicitud->aprobado_por)
                                    {{ $solicitud->aprobadoPor->name }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                @if (Auth::user()->isAdmin() || Auth::user()->id == 3)
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="mostrarConfirmacionEliminar('{{ route('vacaciones.eliminar', $solicitud->id) }}')">
                                        <i class="fas fa-trash fa-md"></i> Eliminar
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <!-- Modal para crear una solicitud de vacaciones -->
        <div class="modal fade" id="crearSolicitudModal" tabindex="-1" aria-labelledby="crearSolicitudModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="crearSolicitudModalLabel">Crear Solicitud de Vacaciones</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('vacaciones.crear') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="fecha_solicitud" class="form-label">Fecha de Solicitud</label>
                                <input type="date" class="form-control" id="fecha_solicitud" name="fecha_solicitud"
                                    value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio"
                                    required onchange="calcularDias()">
                            </div>
                            <div class="mb-3">
                                <label for="fecha_fin" class="form-label">Fecha de Fin</label>
                                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required
                                    onchange="calcularDias()">
                            </div>
                            <div class="mb-3">
                                <label for="dias_solicitados" class="form-label">Días Solicitados</label>
                                <input type="number" class="form-control" id="dias_solicitados" name="dias_solicitados"
                                    readonly>
                            </div>

                            <div class="mb-3">
                                <label for="comentarios" class="form-label">Comentarios</label>
                                <textarea class="form-control" id="comentarios" name="comentarios" rows="3"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Enviar Solicitud</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Confirmación -->
        <div class="modal fade" id="confirmarAprobacionModal" tabindex="-1"
            aria-labelledby="confirmarAprobacionModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmarAprobacionModalLabel">Confirmar Aprobación
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        ¿Estás seguro de que deseas aprobar esta Solicitud de Vacación?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="confirmarAprobacionBtn">Confirmar</button>
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
                        <h5 class="modal-title" id="confirmarEliminacionModalLabel">Confirmar Eliminación</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        ¿Estás seguro de que deseas eliminar esta solicitud de vacaciones?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <form id="formEliminarSolicitud" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Función para calcular los días solicitados entre las fechas de inicio y fin
        function calcularDias() {
            const fechaInicio = document.getElementById('fecha_inicio').value;
            const fechaFin = document.getElementById('fecha_fin').value;

            if (fechaInicio && fechaFin) {
                const startDate = new Date(fechaInicio);
                const endDate = new Date(fechaFin);
                const timeDifference = endDate - startDate; // Diferencia en milisegundos

                if (timeDifference >= 0) {
                    const diasSolicitados = timeDifference / (1000 * 3600 * 24) + 1; // Convertir a días
                    document.getElementById('dias_solicitados').value = diasSolicitados;
                } else {
                    document.getElementById('dias_solicitados').value = 0;
                }
            }
        }

        // Función para mostrar el modal de confirmación
        function mostrarConfirmacion(select) {
            const estadoSeleccionado = select.value;
            const fila = select.closest('tr');
            const nombreEmpleado = fila.querySelector('td:nth-child(2)').textContent;

            if (estadoSeleccionado === "Aprobado") {
                const modal = new bootstrap.Modal(document.getElementById('confirmarAprobacionModal'));
                modal.show();

                // Mostrar detalles del permiso en el modal
                document.querySelector('.modal-body').innerHTML = `
            ¿Estás seguro de que deseas aprobar el permiso de <strong>${nombreEmpleado}</strong>?
        `;

                const formulario = select.closest('.formEstado');
                document.getElementById('confirmarAprobacionBtn').onclick = function() {
                    formulario.submit();
                    modal.hide();
                };
            } else {
                select.closest('.formEstado').submit();
            }
        }

        // Función para cambiar el filtro de mes o semana
        function cambiarFiltro() {
            const filtro = document.getElementById('filtro').value;
            const filtroMes = document.getElementById('filtro-mes');
            const filtroSemana = document.getElementById('filtro-semana');

            if (filtro === 'mes') {
                filtroMes.style.display = 'block';
                filtroSemana.style.display = 'none';
            } else {
                filtroMes.style.display = 'none';
                filtroSemana.style.display = 'block';
            }
        }

        function mostrarDiasSemana() {
            document.getElementById("dias-semana").style.display = "block";
        }

        // Inicializar DataTable
        $(document).ready(function() {
            $('#solicitudesTable').DataTable({
                "language": {
                    "lengthMenu": "Mostrar _MENU_ registros por página",
                    "zeroRecords": "No se encontraron registros",
                    "info": "Mostrando la página _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay registros disponibles",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)",
                    "search": "Buscar:",
                    "paginate": {
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                }
            });
        });

        function mostrarConfirmacionEliminar(url) {
            const modal = new bootstrap.Modal(document.getElementById('confirmarEliminacionModal'));
            modal.show();

            // Configurar el formulario de eliminación
            const formEliminar = document.getElementById('formEliminarSolicitud');
            formEliminar.action = url;

            // Limpiar el formulario al cerrar el modal
            document.getElementById('confirmarEliminacionModal').addEventListener('hidden.bs.modal', function() {
                formEliminar.action = '';
            });
        }
    </script>
@endsection
