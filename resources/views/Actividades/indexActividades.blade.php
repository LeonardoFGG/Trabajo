<!--------------------------------------------------------
Nombre del Proyecto: ERP
Modulo: Actividades
Version: 1.0
Desarrollado por: Karol Macas
Fecha de Inicio:
Ultima Modificación:
--------------------------------------------------------->
@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-7">


        @if (session('success'))
            <div class="alert alert-success" id="success-message">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->has('error'))
            <div class="alert alert-danger d-flex align-items-center" id="error-message" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2"
                    viewBox="0 0 16 16" role="img" aria-label="Warning:" width="24" height="24"
                    fill="currentColor">
                    <path
                        d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                </svg>
                <div>
                    {{ $errors->first('error') }}
                </div>
            </div>
        @endif



        @if (Auth::user()->isAdmin())
            <!-- Formulario de filtros (solo para el admin) -->
            <form action="{{ route('actividades.indexActividades') }}" method="GET"
            class="mb-4 p-4 shadow bg-light rounded" style="max-width: 1100px; margin: 0 auto;">                <div class="d-flex flex-column flex-md-row gap-3 align-items-center">
                    <div>
                        <label for="filtro">Filtrar por:</label>
                        <select name="filtro" id="filtro" class="form-select" onchange="cambiarFiltro()">
                            <option value="mes" {{ request('filtro') == 'mes' ? 'selected' : '' }}>Mes</option>
                            <option value="semana" {{ request('filtro', 'semana') == 'semana' ? 'selected' : '' }}>Semana
                            </option>
                        </select>


                    </div>



                    <div id="filtro-mes" style="display: {{ request('filtro') == 'mes' ? 'block' : 'none' }};">
                        <label for="mes">Selecciona Mes:</label>
                        <input type="month" name="mes" id="mes" class="form-control"
                            value="{{ request('mes', now()->format('Y-m')) }}">
                    </div>

                    <div id="filtro-semana"
                        style="display: {{ request('filtro', 'semana') == 'semana' ? 'block' : 'none' }};">
                        <label for="semana">Selecciona Semana:</label>
                        <select name="semana" class="form-select" id="semana">
                            <option value="0" {{ request('semana', 0) == 0 ? 'selected' : '' }}>Esta semana</option>
                            <option value="1" {{ request('semana') == 1 ? 'selected' : '' }}>Semana pasada</option>
                            <option value="2" {{ request('semana') == 2 ? 'selected' : '' }}>Hace 2 semanas</option>
                            <option value="3" {{ request('semana') == 3 ? 'selected' : '' }}>Hace 3 semanas</option>
                        </select>
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
                    <div class="d-flex gap-3">
                        <div class="estado-card pastel-morado d-flex flex-column align-items-center justify-content-center p-1 rounded shadow-sm" style="text-align: center; height: 80px;">
                            <i class="fas fa-stopwatch text-white" style="font-size: 1.5rem;"></i>
                            <p class="estado-titulo mb-0" style="font-size: 0.9rem;">{{ $enCursoCount }}</p>
                        </div>
                        <div class="estado-card pastel-naranja d-flex flex-column align-items-center justify-content-center p-1 rounded shadow-sm" style="text-align: center; height: 80px;">
                            <i class="fas fa-hourglass-half text-white" style="font-size: 1.5rem;"></i>
                            <p class="estado-titulo mb-0" style="font-size: 0.9rem;">{{ $pendienteCount }}</p>
                        </div>
                        <div class="estado-card pastel-verde d-flex flex-column align-items-center justify-content-center p-1 rounded shadow-sm" style="text-align: center; height: 80px;">
                            <i class="fas fa-check-circle text-white" style="font-size: 1.5rem;"></i>
                            <p class="estado-titulo mb-0" style="font-size: 0.9rem;">{{ $finalizadoCount }}</p>
                        </div>
                    </div>
                    
                </div>
            </form>
        @else
            <form method="GET" action="{{ route('actividades.indexActividades') }}"
                class="mb-3 p-4 shadow bg-light rounded" style="max-width: 700px; margin: 0 auto;">
                <div class="d-flex flex-column flex-md-row gap-3 align-items-center justify-content-center">

                    <div class="d-flex flex-column gap-2">
                        <label for="filtro" class="form-label fw-semibold" style="font-size: 0.9rem;">Filtrar por:</label>
                        <select name="filtro" id="filtro" class="form-select w-auto" onchange="cambiarFiltro()">
                            <option value="mes" {{ request('filtro') == 'mes' ? 'selected' : '' }}>Mes</option>
                            <option value="semana" {{ request('filtro', 'semana') == 'semana' ? 'selected' : '' }}>Semana
                            </option>
                        </select>
                    </div>

                    <div id="filtro-mes" class="mt-3"
                        style="display: {{ request('filtro') == 'mes' ? 'block' : 'none' }};">
                        <label for="mes" class="form-label" style="font-size: 0.9rem;">Selecciona Mes:</label>
                        <input type="month" name="mes" id="mes" class="form-control"
                            value="{{ request('mes', now()->format('Y-m')) }}">
                    </div>

                    <div id="filtro-semana" class="mt-3"
                        style="display: {{ request('filtro', 'semana') == 'semana' ? 'block' : 'none' }};">
                        <label for="semana" class="form-label" style="font-size: 0.9rem;">Selecciona Semana:</label>
                        <select name="semana" id="semana" class="form-select">
                            <option value="0" {{ request('semana', 0) == 0 ? 'selected' : '' }}>Esta semana</option>
                            <option value="1" {{ request('semana') == 1 ? 'selected' : '' }}>Semana pasada</option>
                            <option value="2" {{ request('semana') == 2 ? 'selected' : '' }}>Hace 2 semanas</option>
                            <option value="3" {{ request('semana') == 3 ? 'selected' : '' }}>Hace 3 semanas</option>
                        </select>
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <button type="submit" class="btn btn-primary d-flex align-items-center"
                            style="font-size: 0.9rem;">
                            <i class="fas fa-filter me-2"></i> Aplicar Filtro
                        </button>
                        <div class="d-flex gap-3">
                            <div class="estado-card pastel-morado d-flex flex-column align-items-center justify-content-center p-1 rounded shadow-sm" style="text-align: center; height: 80px;">
                                <i class="fas fa-stopwatch text-white" style="font-size: 1.5rem;"></i>
                                <p class="estado-titulo mb-0" style="font-size: 0.9rem;">{{ $enCursoCount }}</p>
                            </div>
                            <div class="estado-card pastel-naranja d-flex flex-column align-items-center justify-content-center p-1 rounded shadow-sm" style="text-align: center; height: 80px;">
                                <i class="fas fa-hourglass-half text-white" style="font-size: 1.5rem;"></i>
                                <p class="estado-titulo mb-0" style="font-size: 0.9rem;">{{ $pendienteCount }}</p>
                            </div>
                            <div class="estado-card pastel-verde d-flex flex-column align-items-center justify-content-center p-1 rounded shadow-sm" style="text-align: center; height: 80px;">
                                <i class="fas fa-check-circle text-white" style="font-size: 1.5rem;"></i>
                                <p class="estado-titulo mb-0" style="font-size: 0.9rem;">{{ $finalizadoCount }}</p>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </form>
        @endif






        <div class="table-scroll-buttons d-flex justify-content-between mb-3">
            <button id="scroll-left" class="btn btn-secondary btn-md">
                <i class="fas fa-chevron-left fa-2x"></i>
            </button>
            <button id="scroll-right" class="btn btn-secondary btn-md">
                <i class="fas fa-chevron-right fa-2x"></i>
            </button>
        </div>


        <div class="text-left">
            <a href="{{ route('actividades.create') }}" class="btn btn-primary btn-lg ms-3"
                style="margin-left: 1rem;">Crear Actividad</a>
        </div>

        <div class="table-responsive">
            <!-- Contadores para los estados -->


            <table id="actividades-table" class="table table-hover table-bordered">
                <thead class="thead-dark text-center">
                    <tr>
                        <th>ID</th>
                        <th scope="col">Cliente</th>
						<th scope="col">Productos</th>
                        <th scope="col">Empleado</th>
                        <th scope="col">Descripción</th>
                        <th scope="col">Código Osticket</th>
                        <th scope="col">Fecha de Inicio</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Avance (%)</th>
                        <th scope="col">Observaciones</th>
                        <th scope="col">Tiempo Estimado (min)</th>
                        <th scope="col">Tiempo Real (h y m)</th>
                        <th scope="col">Fecha de Fin</th>
                        <th scope="col">Prioridad</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($actividades as $actividad)
                        <tr>
                            <td>{{ $actividad->id }}</td>
                            <td>{{ $actividad->cliente ? $actividad->cliente->nombre : 'No asignado' }}</td>
                            <td>{{ $actividad->producto->nombre ?? 'N/A' }}</td> 
							<td>{{ $actividad->empleado ? $actividad->empleado->nombre1 . ' ' . $actividad->empleado->apellido1 : 'No asignado' }}
                            </td>
                            <td class="descripcion-limite">{{ $actividad->descripcion }}</td>
                            <td>
                                @if (!empty($actividad->codigo_osticket))
                                    <a href="{{ $actividad->codigo_osticket }}" target="_blank"
                                        rel="noopener noreferrer">
                                        Ver Ticket
                                    </a>
                                @else
                                    <span>No disponible</span>
                                @endif
                            </td>

                            <td>{{ $actividad->fecha_inicio->format('d-m-Y h:i:s A') }}</td>

                            <td>
                                <!-- Muestra el estado actual con el diseño de badge -->
                                <span
                                    class="badge {{ $actividad->estado == 'EN CURSO' ? 'bg-pastel-morado' : ($actividad->estado == 'FINALIZADO' ? 'bg-pastel-verde' : 'bg-pastel-naranja') }}">
                                    {{ $actividad->estado }}
                                </span>

                            </td>

                            <td>
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar" role="progressbar"
                                        style="width: {{ $actividad->avance }}%;"
                                        aria-valuenow="{{ $actividad->avance }}" aria-valuemin="0" aria-valuemax="100">
                                        {{ $actividad->avance }}%
                                    </div>
                                </div>

                                @if (Auth::user()->isEmpleado())
                                    <!-- Mostrar botón de finalizar si la actividad está en curso o pendiente -->
                                    @if ($actividad->estado === 'EN CURSO' || $actividad->estado === 'PENDIENTE')
                                        <form action="{{ route('actividades.updateEstado', $actividad->id) }}"
                                            method="POST" class="mt-2" onsubmit="return confirmarFinalizar(event);">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" name="finalizar" value="1"
                                                class="btn btn-success btn-sm">
                                                <i class="bi bi-check"></i>
                                            </button>
                                        </form>
                                    @else
                                        <!-- Mostrar estado finalizado si la actividad ya terminó -->
                                        <span class="badge bg-success">Finalizado</span>
                                    @endif

                                    <!-- Botón para pausar o reanudar actividad -->
                                    @if ($actividad->estado === 'EN CURSO')
                                        <form action="{{ route('actividades.updateEstado', $actividad->id) }}"
                                            method="POST" class="mt-2">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" name="pausar" value="1"
                                                class="btn btn-warning btn-sm">
                                                <i class="bi bi-pause"></i>
                                            </button>
                                        </form>
                                    @elseif ($actividad->estado === 'PENDIENTE')
                                        <form action="{{ route('actividades.updateEstado', $actividad->id) }}"
                                            method="POST" class="mt-2">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" name="reanudar" value="1"
                                                class="btn btn-primary btn-sm">
                                                <i class="bi bi-play-fill"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endif

                            </td>
                            <td>
                                @if ($actividad->avance < 100)
                                    <!-- Botón para abrir el modal de edición -->
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#modalObservaciones{{ $actividad->id }}">
                                        Ingresar Observaciones
                                    </button>

                                    <!-- Modal para ingresar observaciones -->
                                    <div class="modal fade" id="modalObservaciones{{ $actividad->id }}" tabindex="-1"
                                        aria-labelledby="modalObservacionesLabel{{ $actividad->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="modalObservacionesLabel{{ $actividad->id }}">
                                                        Editar Observaciones
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <form
                                                    action="{{ route('actividades.updateObservaciones', $actividad->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="observaciones"
                                                                class="form-label">Observaciones</label>
                                                            <textarea name="observaciones" class="form-control" rows="6" placeholder="Ingrese observaciones">{{ old('observaciones', $actividad->observaciones) }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancelar</button>
                                                        <button type="submit" class="btn btn-primary">Guardar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <!-- Botón para abrir el modal de visualización -->
                                    <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#modalVerObservaciones{{ $actividad->id }}">
                                        Ver Observaciones
                                    </button>

                                    <!-- Modal para ver observaciones -->
                                    <div class="modal fade" id="modalVerObservaciones{{ $actividad->id }}"
                                        tabindex="-1" aria-labelledby="modalVerObservacionesLabel{{ $actividad->id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="modalVerObservacionesLabel{{ $actividad->id }}">
                                                        Observaciones
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- Observaciones estilizadas -->
                                                    <div class="p-3 bg-light border rounded">
                                                        <p class="mb-0">{{ $actividad->observaciones }}</p>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cerrar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </td>

                            <td>{{ $actividad->tiempo_estimado }}</td>
                            <td>{{ $actividad->estado === 'FINALIZADO' ? ($actividad->tiempo_real_horas ?? 0) . ' h y ' . ($actividad->tiempo_real_minutos ?? 0) . ' min' : 'N/A' }}
                            </td>
                            <td>{{ $actividad->fecha_fin ? $actividad->fecha_fin->format('d-m-Y') : '' }}</td>

                            <td><span
                                    class="badge {{ $actividad->prioridad == 'ALTA' ? 'bg-danger text-light' : ($actividad->prioridad == 'MEDIA' ? 'bg-warning text-dark' : 'bg-success text-light') }}">{{ $actividad->prioridad }}</span>
                            </td>



                            <td class="text-center">
                                @if (Auth::user()->isAdmin())
                                    <a href="{{ route('actividades.show', $actividad->id) }}" class="btn btn-info btn-sm"
                                        title="Ver">
                                        <i class="fas fa-eye fa-md"></i>
                                    </a>
                                    <a href="{{ route('actividades.edit', $actividad->id) }}"
                                        class="btn btn-warning btn-sm" title="Editar">
                                        <i class="fas fa-edit fa-md"></i>
                                    </a>
                                    <form action="{{ route('actividades.destroy', $actividad->id) }}" method="POST"
                                        class="d-inline form-delete">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm btn-delete" title="Eliminar">
                                            <i class="fas fa-trash fa-md"></i>
                                        </button>
                                    </form>
                                @else
                                    <a href="javascript:void(0)" class="btn btn-info btn-sm show-actividad"
                                        data-id="{{ $actividad->id }}" title="Ver">
                                        <i class="fas fa-eye fa-md"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforEach
                </tbody>
            </table>
        </div>

        <div class="modal fade" id="actividadModal" tabindex="-1" aria-labelledby="actividadModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="actividadModalLabel">Detalles de la Actividad</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="modal-content">
                            <!-- El contenido se cargará dinámicamente aquí -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <style>
        .estado-card {
            border-radius: 10px;
            padding: 20px;
            margin: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            color: white;
            font-family: 'Arial', sans-serif;
            text-align: center;
        }

        .pastel-morado {
            background-color: #9c88ff;
            /* Color pastel morado */
        }

        .pastel-naranja {
            background-color: #fbc531;
            /* Color pastel naranja */
        }

        .pastel-verde {
            background-color: #4cd137;
            /* Color pastel verde */
        }

        .estado-titulo {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
        }

        .estado-conteo {
            font-size: 24px;
            font-weight: bold;
            margin: 5px 0 0;
        }



        .table-scroll-buttons {
            display: flex;
            justify-content: space-between;
            position: fixed;
            /* Fija los botones en la pantalla */
            bottom: 20px;
            /* Los coloca un poco más arriba de la parte inferior */
            left: 50%;
            /* Los coloca en el centro horizontal */
            transform: translateX(-50%);
            /* Centra los botones de forma precisa */
            margin-bottom: 20px;
            /* Espacio inferior */
            z-index: 1000;
            /* Asegura que los botones estén por encima de otros elementos */
            background-color: #007bff;
            /* Fondo llamativo (azul) */
            border-radius: 30px;
            /* Bordes redondeados */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            /* Sombra más sutil */
            padding: 8px 16px;
            /* Reduce el espaciado interior */
            transition: all 0.3s ease;
            /* Transición suave para efectos */
        }

        .table-scroll-buttons:hover {
            background-color: #0056b3;
            /* Cambio de color de fondo al pasar el cursor */
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
            /* Aumenta la sombra al pasar el cursor */
        }

        .btn-md {
            padding: 8px 16px;
            /* Tamaño reducido para los botones */
            font-size: 1rem;
            /* Tamaño de texto reducido */
            color: white;
            /* Color de texto blanco */
            background-color: transparent;
            /* Fondo transparente para los botones */
            border: 2px solid white;
            /* Borde blanco */
            border-radius: 20px;
            /* Bordes redondeados */
            transition: all 0.3s ease;
            /* Transición suave para efectos */
        }

        .btn-md:hover {
            background-color: white;
            /* Fondo blanco al pasar el cursor */
            color: #007bff;
            /* Color de texto azul */
        }

        .fa-2x {
            font-size: 1.2rem;
            /* Tamaño reducido para los íconos */
            transition: transform 0.3s ease;
            /* Transición suave para los íconos */
        }

        .btn-md:hover .fa-2x {
            transform: scale(1.1);
            /* Aumenta el tamaño del ícono al pasar el cursor */
        }
        /* ajustar el tamaño del campo descripcion */
        .table .descripcion-limite {
            white-space: nowrap; /* Evita que el texto se rompa en varias líneas */
            overflow: hidden;    /* Esconde el contenido que exceda el contenedor */
            text-overflow: ellipsis; /* Muestra '...' cuando el texto es demasiado largo */
            max-width: 200px;    /* Limita el ancho a 200px (ajustalo como necesites) */
        }
    </style>




    {{-- SweetAlert script --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- DataTables initialization --}}
    <script>
        $(document).ready(function() {
            $('#actividades-table').DataTable({
                responsive: true,

                pageLength: 10, // Número de filas por página
                lengthMenu: [5, 10, 25, 50], // Opciones de paginación
                language: {
                    emptyTable: "No hay actividades registradas",
                    search: "Buscar:",
                    lengthMenu: "Mostrar _MENU_ actividades",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ actividades",
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

            // Función para contar las actividades en cada estado
            function contarEstados() {
                let enCursoCount = 0;
                let pendienteCount = 0;
                let finalizadoCount = 0;

                // Iterar sobre cada fila de la tabla
                $('#actividades-table tbody tr').each(function() {
                    const estado = $(this).find('td').eq(6).text()
                        .trim(); // La columna de estado está en la séptima columna

                    // Contar según el estado
                    if (estado === 'EN CURSO') {
                        enCursoCount++;
                    } else if (estado === 'PENDIENTE') {
                        pendienteCount++;
                    } else if (estado === 'FINALIZADO') {
                        finalizadoCount++;
                    }
                });

                // Actualizar los contadores en el DOM
                $('#count-en-curso').text(enCursoCount);
                $('#count-pendiente').text(pendienteCount);
                $('#count-finalizado').text(finalizadoCount);
            }



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
        });

        document.addEventListener("DOMContentLoaded", () => {
            // Inicializa la DataTable (si usas DataTables)
            $('#example').DataTable();

            const tableWrapper = document.querySelector(".table-responsive");
            const scrollLeftBtn = document.getElementById("scroll-left");
            const scrollRightBtn = document.getElementById("scroll-right");

            // Verifica que tableWrapper se ha seleccionado correctamente
            if (!tableWrapper) {
                console.error("El contenedor de la tabla no se encontró.");
                return;
            }

            // Agrega funcionalidad a los botones
            scrollLeftBtn.addEventListener("click", () => {
                tableWrapper.scrollBy({
                    left: -200,
                    behavior: "smooth"
                });
            });

            scrollRightBtn.addEventListener("click", () => {
                tableWrapper.scrollBy({
                    left: 200,
                    behavior: "smooth"
                });
            });
        });


        function confirmarFinalizar(event) {
            // Mostrar alerta de confirmación
            const confirmacion = confirm("¿Está seguro de que desea finalizar esta actividad?");
            // Si el usuario cancela, detener el envío del formulario
            if (!confirmacion) {
                event.preventDefault();
            }
            return confirmacion;
        }

        function confirmUpdateEstado(id) {
            Swal.fire({
                title: '¿Estás seguro de actualizar el estado?',
                text: '¡Este cambio será guardado!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, actualizar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Encuentra el formulario asociado y envíalo
                    document.querySelector(`#form-estado-${id}`).submit();
                }
            });
        }

        $(document).ready(function() {
            // Desaparecer las notificaciones después de 3 segundos
            setTimeout(function() {
                $('#success-message').fadeOut('slow');
                $('#error-message').fadeOut('slow');
            }, 3000); // 3000 milisegundos = 3 segundos
        });


        $(document).on('click', '.show-actividad', function() {
            const actividadId = $(this).data('id');
            $.ajax({
                url: `/actividades/${actividadId}`, // Ruta para obtener los detalles
                type: 'GET',
                success: function(response) {
                    $('#modal-content').html(response); // Carga el contenido en el modal
                    $('#actividadModal').modal('show'); // Muestra el modal
                },
                error: function() {
                    alert('Error al cargar los detalles de la actividad.');
                }
            });
        });

        function cambiarFiltro() {
            const filtro = document.getElementById('filtro').value;
            document.getElementById('filtro-mes').style.display = filtro === 'mes' ? 'block' : 'none';
            document.getElementById('filtro-semana').style.display = filtro === 'semana' ? 'block' : 'none';
        }

        document.addEventListener('DOMContentLoaded', cambiarFiltro);

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

        // Llamada a la función para ajustar el filtro al cargar la página
        document.addEventListener("DOMContentLoaded", function() {
            cambiarFiltro();
        });
    </script>
@endsection
