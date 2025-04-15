@extends('layouts.app')

@section('content')
    <div class="container mt-4">
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
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h1 class="mb-0">Daily Scrum</h1>
            </div>

            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">


                    <div class="d-flex">

                        <form method="GET" action="{{ route('daily.index') }}" id="filter-form">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                <a href="{{ route('daily.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Crear Registro Diario
                                </a>

                                <!-- Rango de fechas -->
                                <div>
                                    <label for="daterange">Filtrar por rango de fechas:</label>
                                    <input type="text" name="daterange" id="daterange" class="form-control"
                                        placeholder="Selecciona un rango"
                                        value="{{ request('start_date') && request('end_date') ? request('start_date') . ' to ' . request('end_date') : '' }}">

                                    <!-- Inputs ocultos para enviar las fechas reales -->
                                    <input type="hidden" name="start_date" id="start_date" value="{{ request('start_date') }}">
                                    <input type="hidden" name="end_date" id="end_date" value="{{ request('end_date') }}">
                                </div>

                                <script>
                                    $(document).ready(function() {
                                        flatpickr("#daterange", {
                                            mode: "range",
                                            dateFormat: "Y-m-d",
                                            defaultDate: [
                                                "{{ request('start_date', now()->format('Y-m-d')) }}",
                                                "{{ request('end_date', now()->format('Y-m-d')) }}"
                                            ],
                                            locale: {
                                                firstDayOfWeek: 1,
                                                weekdays: {
                                                    shorthand: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
                                                    longhand: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                                                },
                                                months: {
                                                    shorthand: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                                                    longhand: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                                                },
                                            },
                                            onChange: function(selectedDates) {
                                                if (selectedDates.length === 2) {
                                                    document.getElementById('start_date').value = selectedDates[0].toISOString().slice(0, 10);
                                                    document.getElementById('end_date').value = selectedDates[1].toISOString().slice(0, 10);
                                                }
                                            }
                                        });
                                    });
                                </script>                                
                                
                                <div class="col-md-3">
                                    <label for="departamento_id" class="form-label">Departamento:</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-primary text-white">
                                            <i class="fas fa-building"></i>
                                        </span>
                                        <select name="departamento_id" id="departamento_id" class="form-select"
                                            onchange="document.getElementById('filter-form').submit()">
                                            <option value="">-- Todos los departamentos --</option>
                                            @foreach ($departamentos as $departamento)
                                                <option value="{{ $departamento->id }}"
                                                    {{ request('departamento_id') == $departamento->id ? 'selected' : '' }}>
                                                    {{ $departamento->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <label for="empleado_id" class="form-label">Empleado:</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-primary text-white">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <select name="empleado_id" id="empleado_id" class="form-select"
                                            onchange="document.getElementById('filter-form').submit()">
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

                                <!-- Botón para aplicar el filtro -->
                                <div class="d-flex justify-content-center align-items-center mt-3 mt-md-0">
                                    <button type="submit" form="filter-form" class="btn btn-success">
                                        <i class="fas fa-filter"></i> Aplicar Filtro
                                    </button>
                                </div>

                                <div class="d-flex justify-content-center align-items-center mt-3 mt-md-0">
                                    <a href="{{ route('daily.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-sync"></i> Limpiar Filtros
                                    </a>
                                </div>
                            </div>
                        </form>




                    </div>

                </div>


                <div class="table-responsive mt-4">
                    <table id="daily-scrum-table" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Empleado</th>
                                <th>Ayer</th>
                                <th>Hoy</th>
                                <th>Dificultades</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dailies as $daily)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($daily->fecha)->format('d-m-Y') }}</td>
                                    <!-- Fecha formateada -->
                                    <td>{{ $daily->empleado->nombre1 }} {{ $daily->empleado->apellido1 }}</td>
                                    <td>{{ $daily->ayer }}</td>
                                    <td>{{ $daily->hoy }}</td>
                                    <td>{{ $daily->dificultades }}</td>
                                    <td>
                                        @if (Auth::user()->isAdmin())
                                            <a href="{{ route('daily.edit', $daily) }}" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i> 
                                            </a>

                                            <a href="{{ route('daily.show', $daily) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye
                                            "></i>
                                            </a>

                                            <form action="{{ route('daily.destroy', $daily->id) }}" method="POST"
                                                class="d-inline form-delete">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm btn-delete"
                                                    title="Eliminar">
                                                    <i class="fas fa-trash fa-md"></i> 
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('daily.show', $daily) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye
                                            "></i> 
                                            </a>

                                            <form action="{{ route('daily.destroy', $daily->id) }}" method="POST"
                                                class="d-inline form-delete">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm btn-delete"
                                                    title="Eliminar">
                                                    <i class="fas fa-trash fa-md"></i> 
                                                </button>
                                            </form>
                                        @endif


                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            // Inicializa DataTables para la tabla de Daily Scrum
            $('#daily-scrum-table').DataTable({
                responsive: true,
                pageLength: 10, // Número de filas por página
                lengthMenu: [5, 10, 25, 50], // Opciones de paginación
                language: {
                    search: "Buscar:",
                    lengthMenu: "Mostrar _MENU_ registros",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    paginate: {
                        first: "Primera",
                        last: "Última",
                        next: "Siguiente",
                        previous: "Anterior"
                    }
                },
                // Ordenar por fecha de forma descendente por defecto
                order: [
                    [0, 'desc'] // Asumiendo que la primera columna es la fecha
                ]
            });



            // SweetAlert para confirmación de eliminación
            document.querySelectorAll('.form-delete').forEach((form) => {
                form.addEventListener('submit', function(e) {
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
                            form.submit();
                        }
                    });
                });
            });


        });

        // Ocultar mensajes después de 3 segundos
        setTimeout(function() {
            var successMessage = document.getElementById('success-message');
            var errorMessage = document.getElementById('error-message');
            if (successMessage) {
                successMessage.style.display = 'none';
            }
            if (errorMessage) {
                errorMessage.style.display = 'none';
            }
        }, 3000);
    </script>
@endsection
