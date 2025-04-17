@extends('layouts.app')

@section('content')
    <div class="container mt-7">
        <h1 class="text-center mb-8">Listado de Clientes</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="d-flex justify-content-between">
            <a href="{{ route('clientes.create') }}" class="btn btn-primary btn-lg ms-3"
                style="margin-left: 1rem;">Crear Cliente</a>


            @if (Auth::user()->isAdmin() ||
                    Auth::user()->isGerenteGeneral() ||
                    Auth::user()->isAsistenteGerencial() )
                <div>
                    <a href="{{ route('clientes.exportar.productos', 'excel') }}" class="btn btn-success">
                        <i class="fas fa-file-excel"></i> Exportar Excel (Clientes y Productos)
                    </a>
                    <a href="{{ route('clientes.exportar.productos', 'pdf') }}" class="btn btn-danger">
                        <i class="fas fa-file-pdf"></i> Exportar PDF (Clientes y Productos)
                    </a>
                </div>
            @endif
        </div>


        <div class="table-scroll-buttons d-flex justify-content-between mb-3">
            <button id="scroll-left" class="btn btn-secondary btn-md">
                <i class="fas fa-chevron-left fa-2x"></i>
            </button>
            <button id="scroll-right" class="btn btn-secondary btn-md">
                <i class="fas fa-chevron-right fa-2x"></i>
            </button>

        </div>

        <div class="table-responsive">
            <table id="clientes-table" class="table table-bordered table-striped">
                <thead class="thead-dark text-center">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Productos</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Dirección</th>
                        <th scope="col">Teléfono</th>
                        <th scope="col">Email</th>
                        <th scope="col">Contacto</th>
                        <th scope="col">Contrato de Implementacion</th>
                        <th scope="col">Convenio de Datos</th>
                        <th scope="col">Documentos Otros</th>
                        <th scope="col">Valor de los Productos</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clientes as $cliente)
                        <tr>
                            <td>{{ $cliente->id }}</td>
                            <td>
                                @foreach ($cliente->productos as $producto)
                                    <span>{{ $producto->codigo . ' - ' . $producto->nombre }}</span>
                                    @if (!$loop->last)
                                        ,
                                    @endif
                                @endforeach
                            </td>
                            <td>{{ $cliente->nombre }}</td>
                            <td>{{ $cliente->direccion }}</td>
                            <td>{{ $cliente->telefono }}</td>
                            <td>{{ $cliente->email }}</td>
                            <td>{{ $cliente->contacto }}</td>
                            <td>{{ $cliente->total_valor_productos }}</td>
                            <td>
                                @if ($cliente->contrato_implementacion)
                                    <a href="{{ asset('storage/' . $cliente->contrato_implementacion) }}"
                                        class="btn btn-info btn-sm" target="_blank">Ver</a>
                                @else
                                    <span class="text-danger">No tiene contrato de implementación</span>
                                @endif
                            </td>
                            <td>
                                @if ($cliente->convenio_datos)
                                    <a href="{{ asset('storage/' . $cliente->convenio_datos) }}"
                                        class="btn btn-info btn-sm" target="_blank">Ver</a>
                                @else
                                    <span class="text-danger">No tiene convenio de datos</span>
                                @endif
                            </td>
                            <td>
                                @if ($cliente->documento_otros)
                                    @php
                                        $documentos = json_decode($cliente->documento_otros, true) ?? [];
                                    @endphp
                                    @foreach ($documentos as $documento)
                                        <a href="{{ asset('storage/' . $documento) }}" class="btn btn-info btn-sm mb-2"
                                            target="_blank">Ver Documento</a><br>
                                    @endforeach
                                @else
                                    <span class="text-danger">No tiene documentos otros</span>
                                @endif
                            </td>
                            <td>{{ $cliente->estado }}</td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('clientes.show', $cliente->id) }}" class="btn btn-info btn-sm"
                                        title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('clientes.edit', $cliente->id) }}" class="btn btn-warning btn-sm"
                                        title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST"
                                        class="d-inline form-delete">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm btn-delete" title="Eliminar">
                                            <i class="fas fa-trash fa-md"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

    <style>
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
            padding: 3px 10px;
            /* Tamaño reducido para los botones */
            font-size: 1rem;
            /* Tamaño de texto reducido */
            color: white;
            /* Color de texto blanco */
            background-color: transparent;
            /* Fondo transparente para los botones */
            border: 2px solid white;
            /* Borde blanco */
            border-radius: 22px;
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
    </style>

    {{-- SweetAlert script --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // SweetAlert para confirmación de eliminación
        $(document).ready(function() {
            // Configuración de DataTables
            $('#clientes-table').DataTable({
                responsive: true,
                pageLength: 10, // Número de filas por página
                lengthMenu: [5, 10, 25, 50], // Opciones de paginación
                language: {
                    search: "Buscar:",
                    lengthMenu: "Mostrar _MENU_ clientes",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ clientes",
                    paginate: {
                        first: "Primera",
                        last: "Última",
                        next: "Siguiente",
                        previous: "Anterior"
                    }
                },
                order: [
                    [0, 'desc'] // Ordenar por ID de forma descendente por defecto
                ]
            });
        });

        document.querySelectorAll('.form-delete').forEach(form => {
            form.addEventListener('submit', function(event) {
                event.preventDefault();
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
                })
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
    </script>
@endsection
