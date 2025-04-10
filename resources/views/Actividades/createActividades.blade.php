@extends('layouts.app')

@section('content')
    <div class="container mt-4" style="max-width: 700px;">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white text-center">
                        <h2><i class="fas fa-tasks"></i> Crear Nueva Actividad</h2>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('actividades.store') }}" method="POST">
                            @csrf
							
												



                            <!-- Selección del Cliente -->
                            <div class="form-group row mb-3">
                                <label for="cliente_id" class="col-md-5 col-form-label text-md-right">
                                    <strong>Clientes & Cooperativa</strong>
                                </label>

                                <!-- Campo de búsqueda -->
                                <input type="text" id="cliente-search" class="form-control mb-3"
                                    placeholder="Ingrese el texto Para Buscar Cliente...">

                                <!-- Lista de opciones filtradas -->
                                <select name="cliente_id" id="cliente_id" class="form-select" required>
                                    <option value="">Seleccione un cliente & Cooperativa</option>
                                    @foreach ($clientes as $cliente)
                                        <option value="{{ $cliente->id }}"
                                            {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                            {{ $cliente->nombre }}
                                        </option>
                                    @endforeach
                                </select>
								 @if ($errors->has('cliente_id'))
        <small class="text-danger">{{ $errors->first('cliente_id') }}</small>
    @endif
                            </div>

                            <div class="form-group mb-4">
                                <label for="producto_id">Producto</label>
                                <select name="producto_id" id="producto_id" class="form-control" required>
                                    <option value="">Seleccione un producto</option>
                                    @foreach ($productos as $producto)
                                        <option value="{{ $producto->id }}"
                                            {{ old('producto_id', $actividad->producto_id ?? '') == $producto->id ? 'selected' : '' }}>
                                            {{ $producto->nombre }}
                                        </option>
                                    @endforeach
                                </select>
								 @if ($errors->has('producto_id'))
        <small class="text-danger">{{ $errors->first('producto_id') }}</small>
    @endif
                            </div>

                            <div class="form-group">
                                <!-- Selección del Empleado -->
                                @if (Auth::user()->isAdmin())
                                    <div class="form-group row">
                                        <label for="empleado_id"
                                            class="col-md-4 col-form-label text-md-right"><strong>Empleado</strong></label>
                                        <div class="col-md-6">
                                            <select name="empleado_id" id="empleado_id" class="form-select"
                                                onchange="updateEmployeeInfo()">
                                                <option value="">Seleccione un empleado</option>
                                                @foreach ($empleados as $empleado)
                                                    <option value="{{ $empleado->id }}"
                                                        data-departamento-id="{{ $empleado->departamento->id ?? '' }}"
                                                        data-departamento="{{ $empleado->departamento->nombre ?? 'Sin departamento' }}"
                                                        data-cargo-id="{{ $empleado->cargo->id ?? '' }}"
                                                        data-cargo="{{ $empleado->cargo->nombre_cargo ?? 'Sin cargo' }}">
                                                        {{ $empleado->nombre1 }} {{ $empleado->apellido1 }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Selección Automática del Empleado para Empleados -->
                            @if (Auth::user()->isEmpleado())
                                <input type="hidden" name="empleado_id" value="{{ Auth::user()->empleado->id }}">
                            @endif


                            <!-- Descripción -->
                            <div class="form-group row mb-3">
                                <label for="descripcion" class="col-md-4 col-form-label text-md-right">Descripción <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <textarea name="descripcion" class="form-control" placeholder="Describe la actividad">{{ old('descripcion') }}</textarea>
                                </div>
                            </div>

                            <!-- Código OSTicket -->
                            <div class="form-group row mb-3">
                                <label for="codigo_osticket" class="col-md-4 col-form-label text-md-right">Código
                                    Osticket</label>
                                <div class="col-md-6">
                                    <input type="text" name="codigo_osticket" class="form-control"
                                        value="{{ old('codigo_osticket') }}">
                                </div>
                            </div>

                            <!-- Semanal o Diario -->
                            <div class="form-group row mb-3">
                                <label for="semanal_diaria" class="col-md-4 col-form-label text-md-right">Frecuencia
                                    <span class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <select name="semanal_diaria" class="form-select" required>
                                        <option value="">Seleccione una frecuencia</option>
                                        <option value="SEMANAL" {{ old('semanal_diaria') == 'SEMANAL' ? 'selected' : '' }}>
                                            Semanal</option>
                                        <option value="DIARIO" {{ old('semanal_diaria') == 'DIARIO' ? 'selected' : '' }}>
                                            Diario</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Fecha de Inicio-->
                            <div class="form-group row mb-2" style="display: none;">
                                <label for="fecha_inicio" class="col-md-4 col-form-label text-md-right">Fecha de
                                    Inicio</label>
                                <div class="col-md-6">
                                    <input type="date" name="fecha_inicio" class="form-control"
                                        value="{{ old('fecha_inicio', now()->format('Y-m-d')) }}" readonly>
                                </div>
                            </div>

                            <!-- Avance -->
                            <div class="form-group row mb-2 " style="display: none;">
                                <label for="avance" class="col-md-4 col-form-label text-md-right">Avance
                                    (%)<span class="text-danger"> *</span></label>
                                <div class="col-md-6">
                                    <input type="number" name="avance" id="avance" class="form-control" value="0"
                                        readonly>
                                </div>
                            </div>

                            <!-- Observaciones-->
                            <div class="form-group row mb-2">
                                <label for="observaciones"
                                    class="col-md-4 col-form-label text-md-right">Observaciones</label>
                                <div class="col-md-6">
                                    <textarea name="observaciones" class="form-control" placeholder="Describe las Observaciones de la Actividad">{{ old('observaciones') }}</textarea>
                                </div>
                            </div>

                            <!-- Estado-->
                            <div class="form-group row mb-2 " style="display: none;">
                                <label for="estado" class="col-md-4 col-form-label text-md-right">Estado<span
                                        class="text-danger"> *</span></label>
                                <div class="col-md-6">
                                    <input type="text" name="estado" id="estado" class="form-control"
                                        value="PENDIENTE" readonly>
                                </div>
                            </div>

                            <!-- Tiempo Estimado-->
                            <div class="form-group row mb-2">
                                <label for="tiempo_estimado" class="col-md-4 col-form-label text-md-right">Tiempo
                                    Estimado(min)<span class="text-danger"> *</span></label>
                                <div class="col-md-6">
                                    <input type="number" name="tiempo_estimado" class="form-control"
                                        placeholder="Ingrese el tiempo estimado en min"
                                        value="{{ old('tiempo_estimado') }}" min="0" required>
                                </div>
                            </div>

                            <!-- Fecha de Fin-->
                            <div class="form-group row mb-2" style="display: none;">
                                <label for="fecha_fin" class="col-md-4 col-form-label text-md-right">Fecha de
                                    Fin</label>
                                <div class="col-md-6">
                                    <input type="date" name="fecha_fin" class="form-control"
                                        value="{{ old('fecha_fin', now()->format('Y-m-d')) }}" readonly>
                                </div>
                            </div>

                            <!-- Repetitivo-->
                            <div class="form-group row mb-2">
                                <label for="repetitivo" class="col-md-4 col-form-label text-md-right">Repetitivo<span
                                        class="text-danger"> *</span></label>
                                <div class="col-md-6">
                                    <select name="repetitivo" class="form-select" required>
                                        <option value="">Seleccione una opción</option>
                                        <option value="1" {{ old('repetitivo') == '1' ? 'selected' : '' }}>Sí
                                        </option>
                                        <option value="0" {{ old('repetitivo') == '0' ? 'selected' : '' }}>No
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <!-- Prioridad -->
                            <div class="form-group row mb-2">
                                <label for="prioridad" class="col-md-4 col-form-label text-md-right">Prioridad<span
                                        class="text-danger"> *</span></label>
                                <div class="col-md-6">
                                    <select name="prioridad" id="prioridad" class="form-select" required>
                                        <option value="">Seleccione una prioridad</option>
                                        <option value="ALTA" {{ old('prioridad') == 'ALTA' ? 'selected' : '' }}>Alta
                                        </option>
                                        <option value="MEDIA" {{ old('prioridad') == 'MEDIA' ? 'selected' : '' }}>
                                            Media
                                        </option>
                                        <option value="BAJA" {{ old('prioridad') == 'BAJA' ? 'selected' : '' }}>Baja
                                        </option>
                                    </select>
                                </div>
                            </div>

                            @if (Auth::user()->isAdmin())
                                <div class="form-group row mb-2">
                                    <label for="departamento"
                                        class="col-md-4 col-form-label text-md-right">Departamento</label>
                                    <div class="col-md-6">
                                        <input type="hidden" name="departamento_id" id="departamento_id"
                                            value="{{ old('departamento_id', $departamento->id ?? '') }}">
                                        <input type="text" id="departamento" class="form-control"
                                            value="{{ old('departamento', $departamento->nombre ?? '') }}" readonly>
                                    </div>
                                </div>

                                <div class="form-group row mb-2">
                                    <label for="cargo" class="col-md-4 col-form-label text-md-right">Cargo</label>
                                    <div class="col-md-6">
                                        <input type="hidden" name="cargo_id" id="cargo_id"
                                            value="{{ old('cargo_id', $cargo->id ?? '') }}">
                                        <input type="text" id="cargo" class="form-control"
                                            value="{{ old('cargo', $cargo->nombre_cargo ?? '') }}" readonly>
                                    </div>
                                </div>
                            @endif

                            <!-- Se llene automatico el campo de de departamento al que corresponde al empleado -->
                            @if (Auth::user()->isEmpleado())
                                <div class="form-group
                                    row mb-2">
                                    <label for="departamento_id"
                                        class="col-md-4 col-form-label text-md-right">Departamento</label>
                                    <div class="col-md-6">
                                        <!-- Campo oculto para enviar el ID del departamento -->

                                        <input type="hidden" name="departamento_id"
                                            value="{{ Auth::user()->empleado->departamento->id }}">
                                        <!-- Campo visible que muestra el nombre del departamento solo como lectura -->
                                        <textarea class="form-control" readonly>{{ Auth::user()->empleado->departamento->nombre }}</textarea>

                                    </div>
                                </div>

                                <!-- Se llene automatico el campo de cargo al que corresponde al empleado -->
                                <div class="form-group row mb-2">
                                    <label for="cargo_id" class="col-md-4 col-form-label text-md-right">Cargo</label>

                                    <div class="col-md-6">
                                        <!-- Campo oculto para enviar el ID del cargo -->
                                        <input type="hidden" name="cargo_id"
                                            value="{{ Auth::user()->empleado->cargo->id }}">
                                        <!-- Campo visible que muestra el nombre del cargo solo como lectura -->
                                        <input type="text" class="form-control"
                                            value="{{ Auth::user()->empleado->cargo->nombre_cargo }}" readonly>
                                    </div>
                                </div>
                            @endif


                            <!-- Tipo de Error -->
                            <div class="form-group row mb-2">
                                <label for="error" class="col-md-4 col-form-label text-md-right">Tipo de
                                    Error<span class="text-danger"> *</span></label>
                                <div class="col-md-6">
                                    <select name="error" class="form-select" required>
                                        <option value="">Seleccione un tipo de error</option>
                                        <option value="ESTRUCTURA" {{ old('error') == 'ESTRUCTURA' ? 'selected' : '' }}>
                                            Estructura
                                        </option>
                                        <option value="CLIENTE" {{ old('error') == 'CLIENTE' ? 'selected' : '' }}>
                                            Cliente
                                        </option>
                                        <option value="SOFTWARE" {{ old('error') == 'SOFTWARE' ? 'selected' : '' }}>
                                            Software</option>
                                        <option value="MEJORA ERROR"
                                            {{ old('error') == 'MEJORA ERROR' ? 'selected' : '' }}>
                                            Mejora
                                            Error
                                        </option>
                                        <option value="DESARROLLO" {{ old('error') == 'DESARROLLO' ? 'selected' : '' }}>
                                            Desarrollo
                                        </option>

                                        <option value="OTRO" {{ old('error') == 'OTRO' ? 'selected' : '' }}>
                                            Otros
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row mb-2">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-success btn-lg m-2">
                                        <i class="fas fa-save"></i> Guardar
                                    </button>
                                    <a href="{{ route('actividades.indexActividades') }}"
                                        class="btn btn-primary btn-lg m-2">
                                        <i class="fas fa-arrow-left"></i> Volver
                                    </a>
                                </div>
                            </div>


                        </form>
                    </div>

                </div>



            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('cliente-search');
            const clienteSelect = document.getElementById('cliente_id');

            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase(); // Obtener el término de búsqueda
                const options = clienteSelect.querySelectorAll('option'); // Obtener todas las opciones

                // Recorrer todas las opciones y ocultar las que no coinciden
                options.forEach(option => {
                    const optionText = option.textContent
                        .toLowerCase(); // Obtener el texto de la opción en minúsculas

                    // Si la opción incluye el término de búsqueda, mostrarla; si no, ocultarla
                    if (optionText.includes(searchTerm) || searchTerm === '') {
                        option.style.display = ''; // Mostrar la opción
                    } else {
                        option.style.display = 'none'; // Ocultar la opción
                    }
                });
            });
        });

        function updateEmployeeInfo() {
            const empleadoSelect = document.getElementById('empleado_id');
            const selectedOption = empleadoSelect.options[empleadoSelect.selectedIndex];

            // Actualizar datos generales
            document.getElementById('departamento').value = selectedOption.getAttribute('data-departamento') || '';
            document.getElementById('departamento_id').value = selectedOption.getAttribute('data-departamento-id') || '';
            document.getElementById('cargo').value = selectedOption.getAttribute('data-cargo') || '';
            document.getElementById('cargo_id').value = selectedOption.getAttribute('data-cargo-id') || '';

        }

        document.addEventListener('DOMContentLoaded', function() {
            const clienteSelect = document.getElementById('cliente_id');
            const productoSelect = document.getElementById('producto_id');

            clienteSelect.addEventListener('change', function() {
                const clienteId = this.value;

                if (clienteId) {
                    // Limpiar el campo de productos y mostrar un mensaje de carga
                    productoSelect.innerHTML = '<option value="">Cargando productos...</option>';

                    // Realizar la solicitud AJAX
                    fetch(`/productos-por-cliente/${clienteId}`)
                        .then(response => response.json())
                        .then(data => {
                            // Limpiar el campo de productos
                            productoSelect.innerHTML =
                                '<option value="">Seleccione un producto</option>';

                            // Agregar los productos al campo de selección
                            data.forEach(producto => {
                                const option = document.createElement('option');
                                option.value = producto.id;
                                option.textContent = producto.nombre;
                                productoSelect.appendChild(option);
                            });
                        })
                        .catch(error => {
                            console.error('Error al cargar productos:', error);
                            productoSelect.innerHTML =
                                '<option value="">Error al cargar productos</option>';
                        });
                } else {
                    // Si no se selecciona un cliente, limpiar el campo de productos
                    productoSelect.innerHTML = '<option value="">Seleccione un producto</option>';
                }
            });
        });
    </script>
@endsection
