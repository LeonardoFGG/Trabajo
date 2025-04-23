@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white text-center">
                        <h2 class="mb-0"><i class="fas fa-user-plus"></i> Registrar Nuevo Empleado</h2>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger m-3">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="step-indicator mb-4">
                        <ul class="nav nav-pills">
                            <li class="nav-item">
                                <a class="nav-link active" id="linkStep1" style="color: black;"
                                    onclick="showStep(1)">Información Personal</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="linkStep2" style="color: black;" onclick="showStep(2)">Detalles del
                                    Contrato</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" id="linkStep3" style="color: black;" onclick="showStep(3)">Documentación
                                    Requerida</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" id="linkStep4" style="color: black;" onclick="showStep(4)">Información
                                    de Pago </a>
                            </li>

                        </ul>
                    </div>

                    <form id="employeeForm" action="{{ route('empleados.store') }}" method="POST"
                        enctype="multipart/form-data" class="p-4">
                        @csrf
		                <!-- Step 1: Información Personal -->
                        <div class="step" id="step1">
                             <h4 class="text-primary mb-4">Información Personal</h4>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="nombre1" class="form-label">Primer Nombre<span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="nombre1" class="form-control" placeholder="Primer nombre"
                                        required>
                                </div>

                                <div class="col-md-6">
                                    <label for="nombre2" class="form-label">Segundo Nombre</label>
                                    <input type="text" name="nombre2" class="form-control" placeholder="Segundo nombre">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="apellido1" class="form-label">Primer Apellido<span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="apellido1" class="form-control"
                                        placeholder="Primer apellido" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="apellido2" class="form-label">Segundo Apellido<span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="apellido2" class="form-control"
                                        placeholder="Segundo apellido" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="cedula" class="form-label">Cédula<span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="cedula" class="form-control" placeholder="Ingrese cédula"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento<span
                                            class="text-danger">*</span></label>
                                    <input type="date" name="fecha_nacimiento" class="form-control" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="telefono" class="form-label">Teléfono</label>
                                    <input type="text" name="telefono" class="form-control" placeholder="Teléfono">
                                </div>
                                <div class="col-md-6">
                                    <label for="celular" class="form-label">Celular<span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="celular" class="form-control" placeholder="Celular"
                                        required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="correo_institucional" class="form-label">Correo Institucional<span
                                            class="text-danger">*</span></label>
                                    <input type="email" name="correo_institucional" class="form-control"
                                        placeholder="correo@empresa.com" required>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary" onclick="nextStep(1)">Siguiente</button>
                        </div>



                       <div class="step d-none" id="step2">
                            <h4 class="text-primary mb-4">Detalles del Contrato</h4>

                            <div class="row g-3">
                                <!-- Selección de Departamento -->
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select name="departamento_id" id="departamento" class="form-select" required onchange="filterSupervisores()">
                                            <option value="">Selecciona un Departamento</option>
                                            @foreach ($departamentos as $departamento)
                                                <option value="{{ $departamento->id }}"
                                                    data-supervisor-id="{{ $departamento->supervisor_id }}"
                                                    {{ old('departamento_id', $empleado->departamento_id) == $departamento->id ? 'selected' : '' }}>
                                                    {{ $departamento->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="departamento">Departamento <span class="text-danger">*</span></label>
                                    </div>
                                </div>

                                <!-- Selección de Supervisor -->
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select name="supervisor_id" id="supervisor_id" class="form-select">
                                            <option value="">Selecciona un Supervisor</option>
                                            @foreach ($supervisores as $supervisor)
                                                <option value="{{ $supervisor->id }}" 
                                                    {{ old('supervisor_id', $empleado->supervisor_id) == $supervisor->id ? 'selected' : '' }}>
                                                    {{ $supervisor->nombre_supervisor }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="supervisor_id">Supervisor</label>
                                    </div>
                                </div>

                                <!-- Checkbox de Supervisor -->
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="es_supervisor" name="es_supervisor" value="1">
                                        <label class="form-check-label" for="es_supervisor">Es supervisor</label>
                                    </div>
                                </div>

                                <!-- Checkbox de Supervisor Superior -->
                                <div class="col-md-6" id="supervisorSuperiorWrapper" style="display: none;">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="es_supervisor_superior" name="es_supervisor_superior" value="1">
                                        <label class="form-check-label" for="es_supervisor_superior">Es supervisor superior</label>
                                    </div>
                                </div>

                                <!-- Selección de Supervisor Superior -->
                                <div class="col-md-12">
                                    <div class="form-floating">
                                        <select class="form-select" name="supervisor_id" id="supervisor_id">
                                            <option value="">Seleccionar Supervisor Superior</option>
                                            @foreach($supervisoresSuperiores as $supervisor)
                                                <option value="{{ $supervisor->id }}">{{ $supervisor->nombre_supervisor }}</option>
                                            @endforeach
                                        </select>
                                        <label for="supervisor_id">Supervisor Superior</label>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="row g-3">
                                <!-- Selección de Cargo -->
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select name="cargo_id" id="cargo" class="form-select" required>
                                            <option value="">Selecciona un Cargo</option>
                                            @foreach ($cargos as $cargo)
                                                <option value="{{ $cargo->id }}" data-departamento-id="{{ $cargo->departamento_id }}">
                                                    {{ $cargo->nombre_cargo }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="cargo">Cargo <span class="text-danger">*</span></label>
                                    </div>
                                </div>

                                <!-- Fecha de Ingreso -->
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="date" name="fecha_ingreso" class="form-control" id="fecha_ingreso" required>
                                        <label for="fecha_ingreso">Fecha de Ingreso <span class="text-danger">*</span></label>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="row g-3">
                                <!-- Tipo de Jornada -->
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select name="jornada_laboral" id="jornada_laboral" class="form-select" required>
                                            <option value="">Selecciona una Opción</option>
                                            <option value="Tiempo Completo">Tiempo Completo</option>
                                            <option value="Medio Tiempo">Medio Tiempo</option>
                                        </select>
                                        <label for="jornada_laboral">Tipo de Jornada <span class="text-danger">*</span></label>
                                    </div>
                                </div>

                                <!-- Fecha de Contratación -->
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="date" name="fecha_contratacion" class="form-control" id="fecha_contratacion" required>
                                        <label for="fecha_contratacion">Fecha de Contratación <span class="text-danger">*</span></label>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="row g-3">
                                <!-- Fecha de Conclusión -->
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="date" name="fecha_conclusion" class="form-control" id="fecha_conclusion">
                                        <label for="fecha_conclusion">Fecha de Conclusión</label>
                                    </div>
                                </div>

                                <!-- Terminación Voluntaria -->
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select name="terminacion_voluntaria" id="terminacion_voluntaria" class="form-select">
                                            <option value="">Selecciona una Opción</option>
                                            <option value="Si">Sí</option>
                                            <option value="No">No</option>
                                        </select>
                                        <label for="terminacion_voluntaria">Terminación Voluntaria</label>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="row g-3">
                                <!-- Fecha de Recontratación -->
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="date" name="fecha_recontratacion" class="form-control" id="fecha_recontratacion">
                                        <label for="fecha_recontratacion">Fecha de Recontratación</label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-secondary" onclick="prevStep(1)">Anterior</button>
                                <button type="button" class="btn btn-primary" onclick="nextStep(2)">Siguiente</button>
                            </div>
                        </div>

                        

                        <!-- Step 3: Documentos Requeridos -->
                        <div class="step d-none" id="step3">
                            <h4 class="text-primary mb-4">Documentos Requeridos</h4>
                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <label for="curriculum" class="form-label">Currículum</label>
                                    <input type="file" name="curriculum" class="form-control">
                                </div>

                            </div>

                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <label for="contrato" class="form-label">Contrato</label>
                                    <input type="file" name="contrato" class="form-control">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <label for="contrato_confidencialidad" class="form-label">Contrato de
                                        Confidencialidad</label>
                                    <input type="file" name="contrato_confidencialidad" class="form-control">
                                </div>

                            </div>

                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <label for="contrato_consentimiento" class="form-label">Contrato de Consentimiento
                                        de Datos</label>
                                    <input type="file" name="contrato_consentimiento" class="form-control">
                                </div>

                            </div>

                            <button type="button" class="btn btn-secondary" onclick="prevStep(2)">Anterior</button>
                            <button type="button" class="btn btn-primary" onclick="nextStep(3)">Siguiente</button>

                        </div>

                        <!-- Step 4: Informacion de Pago -->
                        <div class="step d-none" id="step4">
                            <h4 class="text-primary mb-4">Información de Pago</h4>

                            <div class="form-group mt-4">
                                <label for="rubros">Selecciona Rubros</label>
                                <div id="rubros" class="d-flex flex-wrap justify-content-center">
                                    @foreach ($rubros as $rubro)
                                        <div class="form-check m-2">
                                            <input class="form-check-input" type="checkbox" name="rubros[]"
                                                id="rubro{{ $rubro->id }}" value="{{ $rubro->id }}">
                                            <label class="form-check-label" for="rubro{{ $rubro->id }}">
                                                {{ $rubro->nombre }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>


                            <div id="montos-container"></div>
                            <!-- Aquí se agregarán dinámicamente los campos de monto -->

                            <button type="button" class="btn btn-secondary" onclick="prevStep(3)">Anterior</button>
                            <button type="submit" class="btn btn-success">Guardar</button>

                        </div>
                    </form>
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


    // Objeto para guardar los valores de los montos ingresados
    const montosIngresados = {};

    document.getElementById('rubros').addEventListener('change', function(event) {
        const montosContainer = document.getElementById('montos-container');

        // Obtener todos los checkboxes seleccionados
        const checkboxes = document.querySelectorAll('#rubros input[type="checkbox"]:checked');
        
        checkboxes.forEach(function(checkbox) {
            const rubroId = checkbox.value;
            const rubroNombre = checkbox.nextElementSibling.textContent;

            // Verificar si el campo ya existe
            if (!document.getElementById(`monto${rubroId}`)) {
                // Crear un div para cada monto
                const montoDiv = document.createElement('div');
                montoDiv.className = 'form-group mb-3';
                montoDiv.id = `monto-div-${rubroId}`; // Div identificador único

                // Etiqueta para el rubro
                const label = document.createElement('label');
                label.textContent = `Monto para ${rubroNombre}`;
                label.htmlFor = `monto${rubroId}`;

                // Campo de entrada para el monto
                const input = document.createElement('input');
                input.type = 'number';
                input.name = `montos[${rubroId}]`;
                input.id = `monto${rubroId}`;
                input.className = 'form-control';
                input.placeholder = 'Ingrese el monto';

                // Restaurar el valor anterior si existe
                if (montosIngresados[rubroId] !== undefined) {
                    input.value = montosIngresados[rubroId];
                }

                // Escuchar el evento de entrada y actualizar el objeto
                input.addEventListener('input', function(event) {
                    montosIngresados[rubroId] = event.target.value;
                    console.log(`Monto para ${rubroNombre}: ${montosIngresados[rubroId]}`);
                });

                // Agregar el label y el input al div
                montoDiv.appendChild(label);
                montoDiv.appendChild(input);

                // Agregar el div al contenedor principal
                montosContainer.appendChild(montoDiv);
            }
        });

        // Verificar si hay checkboxes desmarcados y eliminar sus montos
        const allRubroIds = Array.from(document.querySelectorAll('#rubros input[type="checkbox"]')).map(checkbox => checkbox.value);
        allRubroIds.forEach(rubroId => {
            if (!document.querySelector(`#rubros input[type="checkbox"][value="${rubroId}"]`).checked) {
                const existingMontoDiv = document.getElementById(`monto-div-${rubroId}`);
                if (existingMontoDiv) {
                    montosContainer.removeChild(existingMontoDiv);
                    delete montosIngresados[rubroId]; // Opcional: borrar del objeto montos
                }
            }
        });
    });

    // Función para mostrar un paso
    function showStep(step) {
        document.querySelectorAll('.step').forEach(s => s.classList.add('d-none'));
        document.querySelector(`#step${step}`).classList.remove('d-none');

        document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('active'));
        document.querySelector(`#linkStep${step}`).classList.add('active');
    }

    // Función para avanzar al siguiente paso
    function nextStep(step) {
        // Selecciona el paso actual
        const currentStep = document.querySelector(`#step${step}`);
        const requiredFields = currentStep.querySelectorAll('[required]');

        let isValid = true;

        // Validación de campos requeridos
        requiredFields.forEach(field => {
            if (!field.value) {
                isValid = false;
                field.classList.add('is-invalid'); // Agrega la clase de error
            } else {
                field.classList.remove('is-invalid'); // Remueve la clase de error si está completo
            }
        });

        if (isValid) {
            showStep(step + 1); // Solo avanza si todos los campos están llenos
        } else {
            // Despliega un mensaje de advertencia en el caso de que haya campos vacíos
            alert("Por favor complete todos los campos requeridos antes de continuar.");
        }
    }

    // Función para retroceder al paso anterior
    function prevStep(step) {
        showStep(step - 1);
    }

    // Función para manejar la selección de supervisor
    document.getElementById('es_supervisor').addEventListener('change', function() {
        const supervisorSuperiorWrapper = document.getElementById('supervisorSuperiorWrapper');
        const esSupervisorSuperior = document.getElementById('es_supervisor_superior');
        if (this.checked) {
            supervisorSuperiorWrapper.style.display = 'block';
            esSupervisorSuperior.checked = false; // Resetear supervisor superior si se selecciona supervisor
        } else {
            supervisorSuperiorWrapper.style.display = 'none';
        }
    });
</script>

    @endsection
