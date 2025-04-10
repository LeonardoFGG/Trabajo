@extends('layouts.rrhh')

@section('content')
    <div class="container mt-7">
        <!-- Mensajes de éxito y error -->
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

        <!-- Botones de desplazamiento -->
        <div class="table-scroll-buttons d-flex justify-content-between mb-3">
            <button id="scroll-left" class="btn btn-secondary btn-md">
                <i class="fas fa-chevron-left fa-2x"></i>
            </button>
            <button id="scroll-right" class="btn btn-secondary btn-md">
                <i class="fas fa-chevron-right fa-2x"></i>
            </button>
        </div>

        <!-- Botón para crear nuevo empleado -->
        <div class="text-left">
            <a href="{{ route('empleados.create') }}" class="btn btn-primary mb-3" style="margin-left: 1rem;">Nuevo
                Empleado</a>
        </div>

        <!-- Tabla de empleados -->
        <div class="table-responsive" id="table-wrapper">
            <table id="empleado-table" class="table table-hover table-bordered">
                <!-- Encabezados de la tabla -->
                <thead class="thead-dark text-center">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Cédula</th>
                        <th>Fecha de Nacimiento</th>
                        <th>Celular</th>
                        <th>Correo</th>
                        <th>Departamento</th>
                        <th>Cargo</th>
                        <th>Supervisor</th>
                        <th>Fecha de Contratación</th>
                        <th>Tipo de Jornada</th>
                        <th>Curriculum</th>
                        <th>Contrato</th>
                        <th>Contrato de Confidencialidad</th>
                        <th>Contrato de Consentimiento</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($empleados as $empleado)
                        <tr>
                            <td>{{ $empleado->id }}</td>
                            <td>{{ $empleado->nombre1 . ' ' . $empleado->nombre2 }}</td>
                            <td>{{ $empleado->apellido1 . ' ' . $empleado->apellido2 }}</td>
                            <td>{{ $empleado->cedula }}</td>
                            <td>{{ $empleado->fecha_nacimiento }}</td>
                            <td>{{ $empleado->celular }}</td>
                            <td>{{ $empleado->correo_institucional }}</td>
                            <td>{{ optional($empleado->departamento)->nombre ?? 'N/A' }}</td>
                            <td>{{ optional($empleado->cargo)->nombre_cargo ?? 'N/A' }}</td>
                            <td>
                                @if ($empleado->es_supervisor && !$empleado->supervisor_id)
                                    Supervisor Superior
                                @elseif ($empleado->es_supervisor)
                                    Supervisor
                                @elseif ($empleado->supervisor_id)
                                    {{ $empleado->supervisor->nombre_supervisor ?? 'N/A' }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ $empleado->fecha_contratacion ? \Carbon\Carbon::parse($empleado->fecha_contratacion)->format('d/m/Y') : 'N/A' }}
                            </td>
                            <td>{{ $empleado->jornada_laboral }}</td>
                            <td>
                                @if ($empleado->curriculum)
                                    <a href="{{ asset($empleado->curriculum) }}" target="_blank">Ver Currículum</a>
                                @else
                                    <span class="text-danger">No tiene curriculum</span>
                                @endif
                            </td>
                            <td>
                                @if ($empleado->contrato)
                                    <a href="{{ asset($empleado->contrato) }}" target="_blank">Ver Contrato</a>
                                @else
                                    <span class="text-danger">No tiene contrato</span>
                                @endif
                            </td>
                            <td>
                                @if ($empleado->contrato_confidencialidad)
                                    <a href="{{ asset($empleado->contrato_confidencialidad) }}" target="_blank">Ver
                                        Contrato de Confidencialidad</a>
                                @else
                                    <span class="text-danger">No tiene contrato de confidencialidad</span>
                                @endif
                            </td>
                            <td>
                                @if ($empleado->contrato_consentimiento)
                                    <a href="{{ asset($empleado->contrato_consentimiento) }}" target="_blank">Ver Contrato
                                        de Consentimiento</a>
                                @else
                                    <span class="text-danger">No tiene contrato de consentimiento</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('empleados.show', $empleado->id) }}" class="btn btn-info btn-sm"
                                    title="Ver"><i class="fas fa-eye fa-md"></i></a>
                                <a href="{{ route('empleados.edit', $empleado->id) }}" class="btn btn-warning btn-sm"
                                    title="Editar"><i class="fas fa-edit fa-md"></i></a>
                                <form action="{{ route('empleados.destroy', $empleado->id) }}" method="POST"
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
    </div>

    <style>
        #table-wrapper {
            overflow-x: auto;
            /* Permite el desplazamiento horizontal */
            white-space: nowrap;
            /* Evita que los elementos se envuelvan */
            width: 100%;
            /* Ocupa el ancho disponible */
            max-width: 100vw;
            /* Máximo ancho de la pantalla */
        }

        .table-scroll-buttons {
            display: flex;
            justify-content: space-between;
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            margin-bottom: 20px;
            z-index: 1000;
            background-color: #007bff;
            border-radius: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 8px 16px;
            transition: all 0.3s ease;
        }

        .table-scroll-buttons:hover {
            background-color: #0056b3;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        }

        .btn-md {
            padding: 8px 16px;
            font-size: 1rem;
            color: white;
            background-color: transparent;
            border: 2px solid white;
            border-radius: 20px;
            transition: all 0.3s ease;
        }

        .btn-md:hover {
            background-color: white;
            color: #007bff;
        }

        .fa-2x {
            font-size: 1.2rem;
            transition: transform 0.3s ease;
        }

        .btn-md:hover .fa-2x {
            transform: scale(1.1);
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    $(document).ready(function() {
    $('#empleado-table').DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],
        language: {
            search: "Buscar:",
            lengthMenu: "Mostrar _MENU_ empleados",
            info: "Mostrando _START_ a _END_ de _TOTAL_ empleados",
            paginate: {
                first: "Primera",
                last: "Última",
                next: "Siguiente",
                previous: "Anterior"
            }
        },
        order: [
            [0, 'desc']
        ]
    });

    // SweetAlert para confirmación de eliminación
    $('.form-delete').on('submit', function(event) {
        event.preventDefault();
        var form = this;
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

    // Funcionalidad de los botones de desplazamiento
    const tableWrapper = document.getElementById("table-wrapper");
    const scrollLeftBtn = document.getElementById("scroll-left");
    const scrollRightBtn = document.getElementById("scroll-right");

    if (!tableWrapper) {
        console.error("El contenedor de la tabla no se encontró.");
        return;
    }

    // Evento para mover la tabla hacia la izquierda
    scrollLeftBtn.addEventListener("click", function() {
        tableWrapper.scrollBy({
            left: -400, // Desplaza 400 píxeles a la izquierda
            behavior: "smooth"
        });
    });

    // Evento para mover la tabla hacia la derecha
    scrollRightBtn.addEventListener("click", function() {
        tableWrapper.scrollBy({
            left: 400, // Desplaza 400 píxeles a la derecha
            behavior: "smooth"
        });
    });
});

    </script>
@endsection
