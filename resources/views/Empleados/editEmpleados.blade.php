@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-7">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white text-center">
                        <h1><i class="fas fa-edit"></i> Editar Actividad</h1>
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

                        <form action="{{ route('empleados.update', $empleados->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Sección de Información del Empleado -->
                            <fieldset class="border p-4 mb-4">
                                <legend class="text-primary"><i class="fas fa-user"></i> Información del Empleado</legend>

                                <!-- Nombres y Apellidos -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nombre1">Primer Nombre</label>
                                            <input type="text" class="form-control" id="nombre1" name="nombre1"
                                                value="{{ old('nombre1', $empleados->nombre1) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nombre2">Segundo Nombre</label>
                                            <input type="text" class="form-control" id="nombre2" name="nombre2"
                                                value="{{ old('nombre2', $empleados->nombre2) }}" >
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="apellido1">Primer Apellido</label>
                                            <input type="text" class="form-control" id="apellido1" name="apellido1"
                                                value="{{ old('apellido1', $empleados->apellido1) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="apellido2">Segundo Apellido</label>
                                            <input type="text" class="form-control" id="apellido2" name="apellido2"
                                                value="{{ old('apellido2', $empleados->apellido2) }}" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Fecha de Nacimiento y Cédula -->
                                <div class="row">
                                    <div class="col-md-6 mt-3">
                                        <div class="form-group">
                                            <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                                            <input type="date" class="form-control" id="fecha_nacimiento"
                                                name="fecha_nacimiento"
                                                value="{{ old('fecha_nacimiento', $empleados->fecha_nacimiento) }}"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <div class="form-group">
                                            <label for="cedula">Cédula</label>
                                            <input type="text" class="form-control" id="cedula" name="cedula"
                                                value="{{ old('cedula', $empleados->cedula) }}" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Teléfono y Celular -->
                                <div class="row">
                                    <div class="col-md-6 mt-3">
                                        <div class="form-group">
                                            <label for="telefono">Teléfono</label>
                                            <input type="text" class="form-control" id="telefono" name="telefono"
                                                value="{{ old('telefono', $empleados->telefono) }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <div class="form-group">
                                            <label for="celular">Celular</label>
                                            <input type="text" class="form-control" id="celular" name="celular"
                                                value="{{ old('celular', $empleados->celular) }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
    <!-- Correo Institucional, Departamento -->
    <div class="col-md-6 mt-3">
        <div class="form-group">
            <label for="correo_institucional">Correo Institucional</label>
            <input type="email" class="form-control" id="correo_institucional" name="correo_institucional"
                value="{{ old('correo_institucional', $empleados->correo_institucional) }}" required>
        </div>
    </div>

    <div class="col-md-6 mt-3">
        <div class="form-group">
            <label for="departamento" class="form-label">Departamento <span class="text-danger">*</span></label>
            <select name="departamento_id" id="departamento" class="form-select" required onchange="filterSupervisores()">
                <option value="">Selecciona un Departamento</option>
                @foreach ($departamentos as $departamento)
                    <option value="{{ $departamento->id }}" data-supervisor-id="{{ $departamento->supervisor_id }}"
                        {{ old('departamento_id', $empleados->departamento_id) == $departamento->id ? 'selected' : '' }}>
                        {{ $departamento->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="row">
    <!-- Supervisor y Cargo -->
    <div class="col-md-6 mt-3">
        <div class="form-group">
            <label for="supervisor" class="form-label">Supervisor <span class="text-danger">*</span></label>
        <select name="supervisor_id" id="supervisor" class="form-select">
            <option value="">Selecciona un Supervisor</option>
            @foreach ($supervisores as $supervisor)
                <option value="{{ $supervisor->id }}"
                    {{ old('supervisor_id', $empleados->supervisor_id) == $supervisor->id ? 'selected' : '' }}>
                    {{ $supervisor->nombre_supervisor }}
                </option>
            @endforeach
        </select>
        </div>
        <div class="form-check mt-2">
            <input class="form-check-input" type="checkbox" id="es_supervisor" name="es_supervisor" value="1"
                {{ old('es_supervisor', $empleados->es_supervisor ? 'checked' : '') }}>
            <label class="form-check-label" for="es_supervisor">¿Es Supervisor?</label>
        </div>

	 <div class="form-group" id="supervisorSuperiorWrapper" style="display: {{ old('es_supervisor', $empleados->es_supervisor) ? 'block' : 'none' }};">
            <label for="supervisor_id">Supervisor Superior</label>
            <select name="supervisor_id" id="supervisor_id" class="form-control">
                <option value="">Seleccionar Supervisor</option>
                @foreach ($supervisores as $supervisor)
                    <option value="{{ $supervisor->id }}" {{ old('supervisor_id', $empleados->supervisor_id) == $supervisor->id ? 'selected' : '' }}>
                        {{ $supervisor->nombre_supervisor }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="es_supervisor_superior">¿Es Supervisor Superior?</label>
            <input type="checkbox" name="es_supervisor_superior" id="es_supervisor_superior" {{ old('es_supervisor_superior', $empleado->supervisor->es_supervisor_superior ?? false) ? 'checked' : '' }}>
        </div>

    </div>
	

    <!-- Cargo -->
    <div class="col-md-6 mt-3">
        <div class="form-group">
            <label for="cargo" class="form-label">Cargo <span class="text-danger">*</span></label>
            <select name="cargo_id" id="cargo" class="form-select" required>
                <option value="">Selecciona un Cargo</option>
                @foreach ($cargos as $cargo)
                    <option value="{{ $cargo->id }}" data-departamento-id="{{ $cargo->departamento_id }}"
                        {{ old('cargo_id', $empleados->cargo_id) == $cargo->id ? 'selected' : '' }}>
                        {{ $cargo->nombre_cargo }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>
                                <!-- Jornada Laboral -->
                                <div class="row">
                                    <div class="col-md-6 mt-3">
                                        <div class="form-group">
                                            <label for="jornada_laboral">Jornada Laboral</label>
                                            <select name="jornada_laboral" id="jornada_laboral" class="form-control"
                                                required>
                                                <option value="">Selecciona una Opción</option>
                                                <option value="Medio Tiempo"
                                                    {{ old('jornada_laboral', $empleados->jornada_laboral) == 'Medio Tiempo' ? 'selected' : '' }}>
                                                    Medio Tiempo</option>
                                                <option value="Tiempo Completo"
                                                    {{ old('jornada_laboral', $empleados->jornada_laboral) == 'Tiempo Completo' ? 'selected' : '' }}>
                                                    Tiempo Completo</option>
                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <div class="form-group">
                                            <label for="fecha_ingreso">Fecha de Ingreso</label>
                                            <input type="date" class="form-control" id="fecha_ingreso"
                                                name="fecha_ingreso"
                                                value="{{ old('fecha_ingreso', $empleados->fecha_ingreso) }}" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Fecha de Contratación -->
                                <div class="row">
                                    <div class="col-md-6 mt-3">
                                        <div class="form-group">
                                            <label for="fecha_contratacion">Fecha de Contratación</label>
                                            <input type="date" class="form-control" id="fecha_contratacion"
                                                name="fecha_contratacion"
                                                value="{{ old('fecha_contratacion', $empleados->fecha_contratacion) }}"
                                                required>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <div class="form-group">
                                            <label for="fecha_conclusion">Fecha de Conclusión</label>
                                            <input type="date" class="form-control" id="fecha_conclusion"
                                                name="fecha_conclusion"
                                                value="{{ old('fecha_conclusion', $empleados->fecha_conclusion) }}">
                                        </div>
                                    </div>

                                </div>


                                <div class="row">

                                    <div class="col-md-6 mt-3">
                                        <div class="form-group ">

                                            <label for="terminacion_voluntaria">Terminación
                                                Voluntaria</label>
                                            <select name="terminacion_voluntaria" id="terminacion_voluntaria"
                                                class="form-select" required>
                                                <option value="">Selecciona una Opción</option>
                                                <option value="Si"
                                                    {{ old('terminacion_voluntaria', $empleados->terminacion_voluntaria) == 'Si' ? 'selected' : '' }}>
                                                    Sí</option>
                                                <option value="No"
                                                    {{ old('terminacion_voluntaria', $empleados->terminacion_voluntaria) == 'No' ? 'selected' : '' }}>
                                                    No</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </fieldset>


                            <fieldset class="border p-3 mb-4">
    <legend class="text-primary"><i class="fa-solid fa-file"></i> Documentos</legend>
    <div class="row">
        <div class="form-group col-md-6">
            <label for="curriculum">Currículum</label>
            <input type="file" class="form-control" id="curriculum" name="curriculum">
            @if ($empleados->curriculum)
                <small class="form-text text-muted">Archivo actual: <a href="{{ asset($empleados->curriculum) }}" target="_blank">Ver Currículum</a></small>
            @endif
        </div>
        <div class="form-group col-md-6">
            <label for="contrato">Contrato</label>
            <input type="file" class="form-control" id="contrato" name="contrato">
            @if ($empleados->contrato)
                <small class="form-text text-muted">Archivo actual: <a href="{{ asset($empleados->contrato) }}" target="_blank">Ver Contrato</a></small>
            @endif
        </div>
    </div>

    <!-- Contratos adicionales -->
    <div class="row">
        <div class="form-group col-md-6">
            <label for="contrato_confidencialidad">Contrato de Confidencialidad</label>
            <input type="file" class="form-control" id="contrato_confidencialidad" name="contrato_confidencialidad">
            @if ($empleados->contrato_confidencialidad)
                <small class="form-text text-muted">Archivo actual: <a href="{{ asset($empleados->contrato_confidencialidad) }}" target="_blank">Ver Contrato de Confidencialidad</a></small>
            @endif
        </div>
        <div class="form-group col-md-6">
            <label for="contrato_consentimiento">Contrato de Consentimiento de Datos</label>
            <input type="file" class="form-control" id="contrato_consentimiento" name="contrato_consentimiento">
            @if ($empleados->contrato_consentimiento)
                <small class="form-text text-muted">Archivo actual: <a href="{{ asset($empleados->contrato_consentimiento) }}" target="_blank">Ver Contrato de Consentimiento de Datos</a></small>
            @endif
        </div>
    </div>
</fieldset>

                            <!-- Información de Pago -->

                            <fieldset class="border p-4 mb-4">
                                <legend class="text-primary"><i class="fa-solid fa-dollar-sign"></i> Información de Pago
                                </legend>
                                <div class="form-group">
                                    <label for="rubros">Selecciona Rubros</label>
                                    <div id="rubros" class="d-flex justify-content-center flex-wrap">
                                        @foreach ($rubros as $rubro)
                                            <div class="form-check m-2">
                                                <input class="form-check-input" type="checkbox" name="rubros[]"
                                                    id="rubro{{ $rubro->id }}" value="{{ $rubro->id }}"
                                                    {{ in_array($rubro->id, $empleados->rubros->pluck('id')->toArray()) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="rubro{{ $rubro->id }}">
                                                    {{ $rubro->nombre }}
                                                </label>
                                                <!-- Agregar campo para monto -->
                                                <div class="mt-2">
                                                    <input type="number" class="form-control"
                                                        name="monto_rubro[{{ $rubro->id }}]"
                                                        value="{{ $empleados->rubros->contains('id', $rubro->id) ? $empleados->rubros->where('id', $rubro->id)->first()->pivot->monto : 0 }}"
                                                        placeholder="Monto" step="0.01">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </fieldset>




                            <!-- Botones -->
                            <div class="d-flex justify-content-between mt-4">
                                <button type="submit" class="btn btn-primary">Actualizar</button>
                                <a href="{{ route('empleados.indexEmpleados') }}" class="btn btn-secondary">Volver</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function filterSupervisores() {
    var departamentoId = document.getElementById('departamento').value;

    // Filtrar supervisores
    if (departamentoId) {
        fetch(`/supervisores/departamento/${departamentoId}`)
            .then(response => response.json())
            .then(data => {
                var supervisorSelect = document.getElementById('supervisor_id'); // Este es el select de supervisor
                supervisorSelect.innerHTML = '<option value="">Selecciona un Supervisor</option>';

                data.supervisores.forEach(supervisor => {
                    var option = document.createElement('option');
                    option.value = supervisor.empleado_id;
                    option.textContent = supervisor.nombre_supervisor;

                    // Si el supervisor seleccionado es el supervisor de este departamento, seleccionarlo
                    if (supervisor.empleado_id == document.getElementById('departamento').selectedOptions[0].getAttribute('data-supervisor-id')) {
                        option.selected = true; // Marcar como seleccionado
                    }

                    supervisorSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error:', error));
    } else {
        // Limpiar el campo supervisor si no se ha seleccionado departamento
        var supervisorSelect = document.getElementById('supervisor_id');
        supervisorSelect.innerHTML = '<option value="">Selecciona un Supervisor</option>';
    }

    // Filtrar cargos (si es necesario en tu caso, sigue usando el mismo código que ya tienes)
    var cargoSelect = document.getElementById('cargo');
    var options = cargoSelect.getElementsByTagName('option');
    var cargosDisponibles = false;

    Array.from(options).forEach(option => {
        var cargoDepartamentoId = option.getAttribute('data-departamento-id');
        if (cargoDepartamentoId === departamentoId) {
            option.style.display = 'block'; 
            cargosDisponibles = true; 
        } else {
            option.style.display = 'none'; 
        }
    });

    // Si no hay cargos disponibles, mostrar mensaje
    var noCargosOption = cargoSelect.querySelector('option[disabled]');
    if (!noCargosOption) {
        if (!cargosDisponibles) {
            noCargosOption = document.createElement('option');
            noCargosOption.disabled = true;
            noCargosOption.selected = true;
            noCargosOption.textContent = 'No hay cargos disponibles para este departamento';
            cargoSelect.appendChild(noCargosOption);
        }
    } else {
        if (cargosDisponibles) {
            noCargosOption.remove();
        }
    }
}

  const montosIngresados = {};
        document.getElementById('rubros').addEventListener('change', function(event) {
            const montosContainer = document.getElementById('montos-container');
            montosContainer.innerHTML = '';

            // Obtener todos los checkboxes seleccionados
            const checkboxes = document.querySelectorAll('#rubros input[type="checkbox"]:checked');
            if (checkboxes.length === 0) {
                // Si no hay rubros seleccionados, asignar valor 0 por defecto
                const montoDiv = document.createElement('div');
                montoDiv.className = 'form-group mb-3';

                const label = document.createElement('label');
                label.textContent = 'Monto para Rubros';
                label.htmlFor = 'montoGeneral';

                const montoGeneral = document.createElement('input');
                montoGeneral.type = 'number';
                montoGeneral.className = 'form-control';
                montoGeneral.id = 'montoGeneral';
                montoGeneral.name = 'montos[general]';
                montoGeneral.value = 0;
                montoGeneral.placeholder = 'Monto';

                montoDiv.appendChild(label);
                montoDiv.appendChild(montoGeneral);
                montosContainer.appendChild(montoDiv);
            }

            // Crear inputs para montos de rubros seleccionados
            checkboxes.forEach(function(checkbox) {
                const rubroId = checkbox.value;
                const rubroNombre = checkbox.nextElementSibling.textContent;

                // Crear un div para cada monto
                const montoDiv = document.createElement('div');
                montoDiv.className = 'form-group mb-3';

                // Etiqueta para el rubro
                const label = document.createElement('label');
                label.textContent = `Monto para ${rubroNombre}`;
                label.htmlFor = `monto${rubroId}`;

                // Campo de entrada para el monto
                const montoInput = document.createElement('input');
                montoInput.type = 'number';
                montoInput.className = 'form-control';
                montoInput.id = `monto${rubroId}`;
                montoInput.name = `montos[${rubroId}]`;
                montoInput.value = 0; // Set value to 0 by default
                montoInput.placeholder = 'Ingrese el monto';

                // Agregar los elementos al div
                montoDiv.appendChild(label);
                montoDiv.appendChild(montoInput);

                // Agregar el div al contenedor
                montosContainer.appendChild(montoDiv);
            });
        });

	

    </script>
@endsection
