@extends('layouts.permisos')

@section('content')
    <div class="container mt-4">
        <!-- Mensajes de éxito o error -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h4>Horas No Justificadas del Empleado</h4>
            </div>
            <div class="card-body">
                <!-- Selección de empleado -->
                <form action="{{ route('permisos.indexHoras') }}" method="GET" class="mb-3">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="empleado_id" class="form-label">Seleccionar Empleado:</label>
                            <select name="empleado_id" id="empleado_id" class="form-select" required>
                                <option value="">Seleccionar</option>
                                @foreach ($empleados as $empleado)
                                    <option value="{{ $empleado->id }}" @if ($empleado->id == $empleadoId) selected @endif>
                                        {{ $empleado->nombre1 }} {{ $empleado->apellido1 }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Ver Horas No Justificadas</button>
                        </div>
                    </div>
                </form>

                @if ($empleadoId)
                    @php
                        $empleadoSeleccionado = $empleados->firstWhere('id', $empleadoId);
                    @endphp
                    <h5 class="mt-3">Horas No Justificadas de {{ $empleadoSeleccionado->nombre1 }} {{ $empleadoSeleccionado->apellido1 }}</h5>

                    <div class="table-responsive">
                        <table id="tablaHorasNoJustificadas" class="table table-striped table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>ID Permiso</th>
                                    <th>Tipo de Permiso</th>
                                    <th>Fecha del permiso</th>
                                    <th>Horas No Justificadas</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalHorasNoJustificadas = 0; @endphp
                                @foreach ($permisos as $permiso)
                                    @if ($permiso->empleado_id == $empleadoId && !$permiso->justificado && $permiso->estado === 'Aprobado')
                                        @php
                                            $horaSalida = \Carbon\Carbon::createFromFormat('H:i:s', $permiso->hora_salida);
                                            $horaRegreso = \Carbon\Carbon::createFromFormat('H:i:s', $permiso->hora_regreso);
                                            $duracion = $horaSalida->diffInMinutes($horaRegreso) / 60;
                                            $totalHorasNoJustificadas += $duracion;
                                        @endphp
                                        <tr>
                                            <td>{{ $permiso->id }}</td>
                                            <td>{{ $permiso->tipo_permiso }}</td>
                                            <td>{{ $permiso->fecha_salida }}</td>
                                            <td>{{ number_format($duracion, 2) }} horas</td>
                                            <td>
                                                <span class="badge bg-warning text-dark">No Justificado</span>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <h5>Total de horas no justificadas: 
                            <strong>
                                {{ isset($horasNoJustificadasPorEmpleado[$empleadoId]) ? number_format($horasNoJustificadasPorEmpleado[$empleadoId], 2) : '0.00' }}
                            </strong> horas
                        </h5>

                        <h5>Horas No Justificadas Acumuladas (Sobrante actual): 
                            <strong>
                                {{ isset($sobrantePorEmpleado[$empleadoId]) ? number_format($sobrantePorEmpleado[$empleadoId], 2) : '0.00' }}
                            </strong> horas
                        </h5>
                    </div>

                    @if (isset($horasNoJustificadasPorEmpleado[$empleadoId]) && $horasNoJustificadasPorEmpleado[$empleadoId] >= 8)
                        <form action="{{ route('permisos.calcularHoras') }}" method="POST" class="mt-3">
                            @csrf
                            <input type="hidden" name="empleado_id" value="{{ $empleadoId }}">
                            <button type="submit" class="btn btn-danger w-100">Calcular y Descontar Vacaciones</button>
                        </form>
                    @else
                        <p class="text-muted mt-3">No se necesita descontar vacaciones ya que las horas no justificadas no superan las 8 horas.</p>
                    @endif
                @else
                    <p class="text-muted mt-3">Selecciona un empleado para ver sus horas no justificadas.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Agregar DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#tablaHorasNoJustificadas').DataTable({
                "language": {
                    "lengthMenu": "Mostrar _MENU_ registros por página",
                    "zeroRecords": "No se encontraron registros",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay registros disponibles",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)",
                    "search": "Buscar:",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                }
            });
        });
    </script>
@endsection