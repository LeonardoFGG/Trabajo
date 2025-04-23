@extends('layouts.permisos')

@section('content')
    <div class="container">

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if (session('info'))
            <div class="alert alert-info">
                {{ session('info') }}
            </div>
        @endif

        <h1>Listado de Permisos</h1>


        @if (Auth::user()->isAdmin() || Auth::user()->isGerenteGeneral() || Auth::user()->isAsistenteGerencial())
            <!-- Formulario de filtros -->
            <form action="{{ route('permisos.index') }}" method="GET" class="mb-4 p-4 shadow bg-light rounded"
                style="max-width: 1100px; margin: 0 auto;">
                <div class="d-flex flex-column flex-md-row gap-3 align-items-center">

                    <!-- Nuevo selector de fechas por rango -->
                    <div>
                        <label for="daterange">Seleccionar Rango de Fechas:</label>
                        <!-- Input para seleccionar el rango de fechas -->
                        <input type="text" name="daterange" id="daterange" class="form-control"
                            placeholder="Selecciona un rango"
                            value="{{ request('start_date') && request('end_date') ? request('start_date') . ' to ' . request('end_date') : '' }}">

                        <!-- Inputs ocultos para enviar las fechas reales -->
                        <input type="hidden" name="start_date" id="start_date" value="{{ request('start_date') }}">
                        <input type="hidden" name="end_date" id="end_date" value="{{ request('end_date') }}">
                    </div>

                    <script>
                        flatpickr("#daterange", {
                            mode: "range", // Modo de selección de rango
                            dateFormat: "Y-m-d", // Formato de la fecha
                            defaultDate: [
                                "{{ request('start_date', now()->format('Y-m-d')) }}", // Fecha inicial por defecto
                                "{{ request('end_date', now()->format('Y-m-d')) }}" // Fecha final por defecto
                            ],
                            locale: {
                                firstDayOfWeek: 1,
                                weekdays: {
                                    shorthand: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
                                    longhand: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                                },
                                months: {
                                    shorthand: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                                    longhand: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto',
                                        'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
                                    ],
                                },
                            },
                            onChange: function(selectedDates) {
                                // Cuando se seleccionan ambas fechas, actualizamos los campos ocultos
                                if (selectedDates.length === 2) {
                                    document.getElementById('start_date').value = selectedDates[0].toISOString().slice(0, 10);
                                    document.getElementById('end_date').value = selectedDates[1].toISOString().slice(0, 10);
                                }
                            }
                        });
                    </script>

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


        <!-- Botón para que solo el empleado pueda enviar solicitud -->
        @if (Auth()->user()->isEmpleado())
            <div class="card mb-4 shadow-sm" style="max-width: 1100px; margin: 0 auto;">
                <div class="card-body">
                    <!-- Botón de solicitud de permiso (separado del formulario) -->
                    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="fas fa-plus-circle"></i> Solicitud de Permiso
                    </button>

                    <!-- Formulario de filtrado (independiente) -->
                    <form action="{{ route('permisos.index') }}" method="GET" class="mb-0">
                        <div class="d-flex flex-column flex-md-row gap-3 align-items-center">
                            <!-- Nuevo selector de fechas por rango -->
                            <div>
                                <label for="daterange">Seleccionar Rango de Fechas:</label>
                                <!-- Input para seleccionar el rango de fechas -->
                                <input type="text" name="daterange" id="daterange" class="form-control"
                                    placeholder="Selecciona un rango"
                                    value="{{ request('start_date') && request('end_date') ? request('start_date') . ' to ' . request('end_date') : '' }}">

                                <!-- Inputs ocultos para enviar las fechas reales -->
                                <input type="hidden" name="start_date" id="start_date"
                                    value="{{ request('start_date') }}">
                                <input type="hidden" name="end_date" id="end_date" value="{{ request('end_date') }}">
                            </div>

                            <script>
                                flatpickr("#daterange", {
                                    mode: "range", // Modo de selección de rango
                                    dateFormat: "Y-m-d", // Formato de la fecha
                                    defaultDate: [
                                        "{{ request('start_date', now()->format('Y-m-d')) }}", // Fecha inicial por defecto
                                        "{{ request('end_date', now()->format('Y-m-d')) }}" // Fecha final por defecto
                                    ],
                                    locale: {
                                        firstDayOfWeek: 1,
                                        weekdays: {
                                            shorthand: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
                                            longhand: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                                        },
                                        months: {
                                            shorthand: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                                            longhand: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto',
                                                'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
                                            ],
                                        },
                                    },
                                    onChange: function(selectedDates) {
                                        // Cuando se seleccionan ambas fechas, actualizamos los campos ocultos
                                        if (selectedDates.length === 2) {
                                            document.getElementById('start_date').value = selectedDates[0].toISOString().slice(0, 10);
                                            document.getElementById('end_date').value = selectedDates[1].toISOString().slice(0, 10);
                                        }
                                    }
                                });
                            </script>

                            <div class="mt-3 mt-md-0">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter"></i> Aplicar Filtro
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <table class="table" id="permisosTable" class="table table-hover table-bordered">
            <thead class="thead-dark text-center">
                <tr>
                    <th>ID</th>
                    <th>Empleado</th>
                    <th>Fecha de Salida</th>
                    <th>Hora de Salida</th>
                    <th>Hora de Regreso</th>
                    <th>Duración</th>
                    <th>Tipo de Permiso</th>
                    <th>Anexo</th>
                    <th>Motivo</th>
                    <th>Estado</th>
                    <th>Justicación</th>
                    <th>Aprobado Por</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($permisos as $permiso)
                    <tr>
                        <td>{{ $permiso->id }}</td>
                        <td>{{ $permiso->empleado->nombre1 . ' ' . $permiso->empleado->apellido1 }}</td>
                        <td>
                            @if ($permiso->fecha_salida)
                                {{ \Carbon\Carbon::parse($permiso->fecha_salida)->format('Y-m-d') }}
                            @else
                                No disponible
                            @endif
                        <td>
                            @if ($permiso->hora_salida && preg_match('/^\d{2}:\d{2}:\d{2}$/', $permiso->hora_salida))
                                {{ \Carbon\Carbon::createFromFormat('H:i:s', $permiso->hora_salida)->format('H:i') }}
                            @else
                                No disponible
                            @endif
                        </td>
                        <td>
                            @if ($permiso->hora_regreso && preg_match('/^\d{2}:\d{2}:\d{2}$/', $permiso->hora_regreso))
                                {{ \Carbon\Carbon::createFromFormat('H:i:s', $permiso->hora_regreso)->format('H:i') }}
                            @else
                                No disponible
                            @endif
                        </td>


                        <td>
                            @if ($permiso->duracion && preg_match('/^\d{2}:\d{2}:\d{2}$/', $permiso->duracion))
                                {{ \Carbon\Carbon::createFromFormat('H:i:s', $permiso->duracion)->format('H \h i\m') }}
                            @else
                                No registrado
                            @endif
                        </td>

                        <td>{{ $permiso->tipo_permiso }}</td>
                        <td>
                            @if (Auth::user()->isEmpleado())
                                <button class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#editAnexoModal{{ $permiso->id }}"> <i
                                        class="bi bi-cloud-arrow-up"></i>
                                </button>
                            @endif
                            @if ($permiso->anexos)
                                <a href="{{ asset($permiso->anexos) }}" target="_blank" class="btn btn-info">
                                    <i class="bi bi-file-earmark-text-fill"></i>
                                </a>
                            @else
                                <a href="#" class="btn btn-danger text-decoration-line-through" role="button"
                                    aria-disabled="true"><i class="bi bi-file-earmark-text-fill"></i>
                                </a>
                            @endif
                        </td>
                        <td>{{ $permiso->motivo }}</td>

                        <td>
                            @php
                                $estado = strtolower(trim($permiso->estado));
                            @endphp
                            <span
                                class="badge @if ($estado == 'pendiente') bg-warning
                               @elseif($estado == 'aprobado') bg-success
                               @else bg-danger @endif"
                                style="font-size: 1.1rem;">
                                {{ $permiso->estado }}
                            </span>



                            <!-- Formulario para cambiar el estado (Compact Styling) -->
                            @if (Auth::user()->isAdmin() ||
                                    Auth::user()->empleado->es_supervisor ||
                                    Auth::user()->isGerenteGeneral() ||
                                    Auth::user()->isAsistenteGerencial())
                                <form action="{{ route('permisos.updateEstado', $permiso->id) }}" method="POST"
                                    class="formEstado mt-1">
                                    @csrf
                                    @method('PATCH')
                                    <!-- Campos ocultos para mantener los filtros -->
                                    <input type="hidden" name="empleado_id" value="{{ request('empleado_id') }}">
                                    <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                                    <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                                    <input type="hidden" name="filtro" value="{{ request('filtro') }}">
                                    <input type="hidden" name="semana" value="{{ request('semana') }}">
                                    <input type="hidden" name="mes" value="{{ request('mes') }}">
                                    <input type="hidden" name="aprobado_por" value="{{ Auth::user()->id }}">

                                    <div class="input-group input-group-sm">
                                        <select name="estado" id="estado{{ $permiso->id }}"
                                            class="form-select form-select-sm"
                                            style="font-size: 0.875rem; padding: 0.25rem 0.5rem;"
                                            onchange="mostrarConfirmacion(this)">
                                            <option value="Pendiente"
                                                {{ $permiso->estado == 'Pendiente' ? 'selected' : '' }}>Pendiente
                                            </option>
                                            <option value="Aprobado"
                                                {{ $permiso->estado == 'Aprobado' ? 'selected' : '' }}>Aprobado
                                            </option>
                                            <option value="Rechazado"
                                                {{ $permiso->estado == 'Rechazado' ? 'selected' : '' }}>Rechazado
                                            </option>
                                        </select>
                                    </div>
                                </form>
                            @endif
                        </td>


                        <td>
                            @php
                                $justificacion = strtolower(trim($permiso->justificado)); // Cambiado a $permiso->justificado
                            @endphp
                            <span class="badge @if ($justificacion == '1') bg-success @else bg-danger @endif"
                                style="font-size: 1.2rem;">
                                {{ $permiso->justificado ? 'Justificado' : 'Sin Justificar' }}
                            </span>

                            @if (Auth::user()->isAdmin() ||
                                    Auth::user()->isSupervisor() ||
                                    Auth::user()->isGerenteGeneral() ||
                                    Auth::user()->isAsistenteGerencial())
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox"
                                        id="flexSwitchCheck{{ $permiso->id }}"
                                        {{ $permiso->justificado ? 'checked' : '' }}
                                        onchange="toggleJustificacion({{ $permiso->id }})">
                                    <label class="form-check-label"
                                        for="flexSwitchCheck{{ $permiso->id }}">Justificado</label>
                                </div>
                            @endif
                        </td>

                        <td>
                            @if ($permiso->aprobado_por)
                                {{ $permiso->aprobadoPor->name }} <!-- Mostrar el nombre del usuario que aprobó -->
                            @else
                                N/A
                            @endif
                        </td>


                        <td class="align-middle">

                            <!-- Botón para editar la hora de regreso -->
                            <button class="btn btn-info mb-2" data-bs-toggle="modal"
                                data-bs-target="#editHoraRegresoModal{{ $permiso->id }}">
                                <i class="fas fa-edit fa-md"></i> <i class="fas fa-clock"></i> Editar Hora
                            </button>

                            <!-- Otras acciones -->
                            @if (Auth::user()->isAdmin() || Auth::user()->isGerenteGeneral() || Auth::user()->isAsistenteGerencial())
                                <!-- Botón de Editar -->
                                <div class="d-flex flex-column">
                                    <button class="btn btn-warning btn-sm mb-2" data-bs-toggle="modal"
                                        data-bs-target="#editModal{{ $permiso->id }}">
                                        <i class="fas fa-edit fa-md"></i> Motivo
                                    </button>

                                    <!-- Botón de Eliminar -->
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="mostrarConfirmacionEliminar({{ $permiso->id }}, '{{ $permiso->empleado->nombre1 }} {{ $permiso->empleado->apellido1 }}')">
                                        <i class="fas fa-trash fa-md"></i> Eliminar
                                    </button>

                                    <!-- Formulario de Eliminación (oculto) -->
                                    <form action="{{ route('permisos.destroy', $permiso->id) }}" method="POST"
                                        id="formEliminar{{ $permiso->id }}" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            @endif

                        </td>


                        <!-- Modal de Edición para Admin y un empleado especifico -->
                        @if (Auth::user()->isAdmin() || Auth::user()->isGerenteGeneral() || Auth::user()->isAsistenteGerencial())
                            <div class="modal fade" id="editModal{{ $permiso->id }}" tabindex="-1"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Editar Permiso</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('permisos.update', $permiso->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <!-- Campos ocultos para mantener los filtros -->
                                                <input type="hidden" name="empleado_id"
                                                    value="{{ request('empleado_id') }}">
                                                <input type="hidden" name="start_date"
                                                    value="{{ request('start_date') }}">
                                                <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                                                <input type="hidden" name="filtro" value="{{ request('filtro') }}">
                                                <input type="hidden" name="semana" value="{{ request('semana') }}">
                                                <input type="hidden" name="mes" value="{{ request('mes') }}">
                                                <div class="mb-3">
                                                    <label for="motivo" class="form-label">Motivo</label>
                                                    <textarea class="form-control" name="motivo">{{ $permiso->motivo }}</textarea>
                                                </div>
                                                <button type="submit" class="btn btn-success">Actualizar</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="modal fade" id="editAnexoModal{{ $permiso->id }}" tabindex="-1"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Editar Anexo</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('permisos.updateAnexo', $permiso->id) }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('PATCH')
                                            <!-- Campos ocultos para mantener los filtros -->
                                            <input type="hidden" name="empleado_id"
                                                value="{{ request('empleado_id') }}">
                                            <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                                            <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                                            <input type="hidden" name="filtro" value="{{ request('filtro') }}">
                                            <input type="hidden" name="semana" value="{{ request('semana') }}">
                                            <input type="hidden" name="mes" value="{{ request('mes') }}">
                                            <div class="mb-3">
                                                <label for="anexos" class="form-label">Nuevo Anexo</label>
                                                <input type="file" class="form-control" name="anexos" required>
                                            </div>
                                            <button type="submit" class="btn btn-success">Actualizar Anexo</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="editHoraRegresoModal{{ $permiso->id }}" tabindex="-1"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Editar Hora de Regreso</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('permisos.updateHoraRegreso', $permiso->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <!-- Campos ocultos para mantener los filtros -->
                                            <input type="hidden" name="empleado_id"
                                                value="{{ request('empleado_id') }}">
                                            <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                                            <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                                            <input type="hidden" name="filtro" value="{{ request('filtro') }}">
                                            <input type="hidden" name="semana" value="{{ request('semana') }}">
                                            <input type="hidden" name="mes" value="{{ request('mes') }}">
                                            <div class="mb-3">
                                                <label for="hora_regreso" class="form-label">Hora de Regreso</label>
                                                <input type="time" class="form-control" name="hora_regreso"
                                                    value="{{ $permiso->hora_regreso && preg_match('/^\d{2}:\d{2}:\d{2}$/', $permiso->hora_regreso) ? \Carbon\Carbon::createFromFormat('H:i:s', $permiso->hora_regreso)->format('H:i') : '' }}"
                                                    required>
                                            </div>
                                            <button type="submit" class="btn btn-success">Guardar</button>
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
                                        <h5 class="modal-title" id="confirmarAprobacionModalLabel">Confirmar
                                            Aprobación
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        ¿Estás seguro de que deseas aprobar este permiso?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancelar</button>
                                        <button type="button" class="btn btn-primary"
                                            id="confirmarAprobacionBtn">Confirmar</button>
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
                                        <h5 class="modal-title" id="confirmarEliminacionModalLabel">Confirmar
                                            Eliminación
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        ¿Estás seguro de que deseas eliminar este permiso?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancelar</button>
                                        <button type="button" class="btn btn-danger"
                                            id="confirmarEliminacionBtn">Eliminar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>



    <!-- Modal de Crear Permiso para Empleado -->
    @if (Auth::user()->isEmpleado())
        <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Nuevo Permiso</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('permisos.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="fecha_solicitud" class="form-label">Fecha de Solicitud</label>
                                <input type="date" class="form-control" name="fecha_solicitud"
                                    value="{{ now()->toDateString() }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="fecha_salida" class="form-label">Fecha de Salida</label>
                                <input type="date" class="form-control" name="fecha_salida" required>
                            </div>
                            <div class="mb-3">
                                <label for="hora_salida" class="form-label">Hora de Salida</label>
                                <input type="time" class="form-control" name="hora_salida" required>
                            </div>
                            <div class="mb-3">
                                <label for="hora_regreso" class="form-label">Hora de Regreso</label>
                                <input type="time" class="form-control" name="hora_regreso" required>
                            </div>
                            <div class="mb-3">
                                <label for="tipo_permiso" class="form-label">Tipo de Permiso</label>
                                <select class="form-control" name="tipo_permiso" id="tipo_permiso" required>
                                    <option value="Personal">Personal</option>
                                    <option value="Enfermedad">Enfermedad</option>
                                    <option value="Estudio">Estudios</option>
                                    <option value="Defuncion">Defuncion</option>
                                    <option value="Maternidad/Paternidad">Maternidad/Paternidad</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>
                            <div class="mb-3" id="motivo_container" style="display: none;">
                                <label for="motivo" class="form-label">Motivo del Permiso</label>
                                <textarea class="form-control" name="motivo"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="anexos" class="form-label">Anexos</label>
                                <input type="file" class="form-control" name="anexos">
                            </div>
                            <button type="submit" class="btn btn-success">Enviar Solicitud</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script>
        $(document).ready(function() {
            $('#permisosTable').DataTable({
                responsive: true,
                pageLength: 10,
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

                dom: 'Bfrtip', // Botones de exportación
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });
        });


        document.addEventListener('DOMContentLoaded', function() {
            // Obtener los elementos de entrada de hora de salida y hora de regreso
            const horaSalidaInput = document.querySelector('input[name="hora_salida"]');
            const horaRegresoInput = document.querySelector('input[name="hora_regreso"]');
            const duracionInput = document.querySelector('input[name="duracion"]');

            // Función para calcular la duración
            // y mostrarla en el campo de duración
            function calcularDuracion() {
                const horaSalida = horaSalidaInput.value;
                const horaRegreso = horaRegresoInput.value;

                if (horaSalida && horaRegreso) {
                    const [horaSalidaH, horaSalidaM] = horaSalida.split(':').map(Number);
                    const [horaRegresoH, horaRegresoM] = horaRegreso.split(':').map(Number);

                    const salida = new Date(0, 0, 0, horaSalidaH, horaSalidaM);
                    const regreso = new Date(0, 0, 0, horaRegresoH, horaRegresoM);

                    if (regreso <= salida) {
                        alert('La hora de regreso debe ser mayor que la hora de salida.');
                        duracionInput.value = '';
                        return;
                    }

                    const diffMs = regreso - salida;
                    const diffH = Math.floor(diffMs / 3600000); // Horas
                    const diffM = Math.floor((diffMs % 3600000) / 60000); // Minutos

                    // Formatear la duración como HH:MM:SS
                    duracionInput.value =
                        `${diffH.toString().padStart(2, '0')}:${diffM.toString().padStart(2, '0')}:00`;
                } else {
                    duracionInput.value = '';
                }
            }

            // Escuchar cambios en los campos de hora de salida y hora de regreso
            horaSalidaInput.addEventListener('change', calcularDuracion);
            horaRegresoInput.addEventListener('change', calcularDuracion);
        });

        document.getElementById('tipo_permiso').addEventListener('change', function() {
            // Mostrar u ocultar el campo de motivo según la selección del tipo de permiso si es "Otro" le pide el motivo y si no lo oculta
            var motivoContainer = document.getElementById('motivo_container');
            motivoContainer.style.display = this.value === 'Otro' ? 'block' : 'none';
        });


        function cambiarFiltro() {
            // Obtener el valor del filtro seleccionado y mostrar/ocultar los campos correspondientes
            const filtro = document.getElementById('filtro').value;
            document.getElementById('filtro-mes').style.display = filtro === 'mes' ? 'block' : 'none';
            document.getElementById('filtro-semana').style.display = filtro === 'semana' ? 'block' : 'none';
        }


        const empleadoSelect = document.getElementById('empleado_id');
        empleadoSelect.addEventListener('change', () => {
            // Mostrar un mensaje o spinner de carga al enviar el formulario
            const loader = document.createElement('div');
            loader.textContent = 'Cargando...';
            loader.style.fontSize = '16px';
            loader.style.color = 'blue';
            loader.style.marginTop = '10px';
            empleadoSelect.parentElement.appendChild(loader);
            empleadoSelect.form.submit();
        });

        function cambiarFiltro() {
            const filtroSeleccionado = document.getElementById('filtro').value;
            const filtroMes = document.getElementById('filtro-mes');
            const filtroSemana = document.getElementById('filtro-semana');

            if (filtroSeleccionado === 'mes') {
                filtroMes.style.display = 'block';
                filtroSemana.style.display = 'none';
            } else if (filtroSeleccionado === 'semana') {
                filtroSemana.style.display = 'block';
                filtroMes.style.display = 'none';
            } else {
                filtroMes.style.display = 'none';
                filtroSemana.style.display = 'none';
            }
        }



        function mostrarDiasSemana() {
            document.getElementById("dias-semana").style.display = "block";
        }


        $(document).ready(function() {
            console.log("jQuery está funcionando correctamente.");
            setTimeout(function() {
                console.log("Desapareciendo notificaciones...");
                $('#success-message').fadeOut('slow');
                $('#error-message').fadeOut('slow');
            }, 3000);
        });

        // Llamada a la función para ajustar el filtro al cargar la página
        document.addEventListener("DOMContentLoaded", function() {
            cambiarFiltro();
        });

        $(document).ready(function() {
            $('#empleado_id').select2({
                placeholder: "Seleccione un empleado",
                allowClear: true
            });

            $('#filtro').select2({
                placeholder: "Seleccione un filtro",
                allowClear: true
            });

            $('#semana').select2({
                placeholder: "Seleccione una semana",
                allowClear: true
            });
        });

        function toggleJustificacion(permisoId) {
            const checkbox = document.getElementById(`flexSwitchCheck${permisoId}`);
            const isJustificado = checkbox.checked;

            fetch(`/permisos/${permisoId}/toggle-justificacion`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        justificado: isJustificado
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Actualizar la interfaz de usuario si es necesario
                        const badge = document.querySelector(`#flexSwitchCheck${permisoId}`).closest('td')
                            .querySelector('.badge');
                        badge.textContent = isJustificado ? 'Justificado' : 'Sin Justificar';
                        badge.classList.toggle('bg-success', isJustificado);
                        badge.classList.toggle('bg-danger', !isJustificado);
                    } else {
                        alert('Error al actualizar la justificación');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });



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

        // Función para mostrar el modal de confirmación de eliminación
        function mostrarConfirmacionEliminar(permisoId, nombreEmpleado) {
            const modal = new bootstrap.Modal(document.getElementById('confirmarEliminacionModal'));
            modal.show();

            // Mostrar el nombre del empleado en el modal
            document.querySelector('.modal-body').innerHTML = `
            ¿Estás seguro de que deseas eliminar el permiso de <strong>${nombreEmpleado}</strong>?
        `;

            // Configurar el botón de confirmar para enviar el formulario
            document.getElementById('confirmarEliminacionBtn').onclick = function() {
                document.getElementById(`formEliminar${permisoId}`).submit();
            };
        }

        document.addEventListener("DOMContentLoaded", function() {
            // Asegurarte de que el campo de fecha tenga un valor por defecto (hoy)
            const fechaInput = document.getElementById("fecha");
            if (!fechaInput.value) {
                const today = new Date().toISOString().split("T")[0]; // Formato YYYY-MM-DD
                fechaInput.value = today;
            }
        });
    </script>
@endsection
