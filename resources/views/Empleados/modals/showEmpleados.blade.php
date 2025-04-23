<div class="modal fade" id="modalShowEmpleado{{ $empleado->id }}" tabindex="-1"
    aria-labelledby="modalShowEmpleadoLabel{{ $empleado->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalShowEmpleadoLabel{{ $empleado->id }}">
                    <i class="fas fa-info-circle"></i> Detalles del Empleado
                </h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal"
                    aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header bg-secondary text-white">
                                <h5><i class="fas fa-user"></i> Información Básica</h5>
                            </div>
                            <div class="card-body">
                                <p><strong>ID:</strong> {{ $empleado->id }}</p>
                                <p><strong>Nombres:</strong> {{ $empleado->nombre1 . ' ' . $empleado->nombre2 }}</p>
                                <p><strong>Apellidos:</strong> {{ $empleado->apellido1 . ' ' . $empleado->apellido2 }}</p>
                                <p><strong>DUI:</strong> {{ $empleado->cedula }}</p>
                                <p><strong>Fecha de Nacimiento:</strong> {{ $empleado->fecha_nacimiento }}</p>
                                <p><strong>Teléfono:</strong> {{ $empleado->telefono }}</p>
                                <p><strong>Celular:</strong> {{ $empleado->celular }}</p>
                                <p><strong>Correo Institucional:</strong> {{ $empleado->correo_institucional }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header bg-secondary text-white">
                                <h5><i class="fas fa-briefcase"></i> Información Laboral</h5>
                            </div>
                            <div class="card-body">
                                <p><strong>Departamento:</strong> {{ $empleado->departamento->nombre }}</p>
                                <p><strong>Cargo:</strong> {{ $empleado->cargo->nombre_cargo }}</p>
                                <p><strong>Supervisor:</strong>
                                    @if ($empleado->supervisor)
                                        {{ $empleado->supervisor->nombre_supervisor }}
                                    @else
                                        Supervisor Superior
                                    @endif
                                </p>
                                <p><strong>Tipo de Jornada:</strong> {{ $empleado->jornada_laboral }}</p>
                                <p><strong>Fecha de Ingreso:</strong>
                                    {{ $empleado->fecha_ingreso ? \Carbon\Carbon::parse($empleado->fecha_ingreso)->format('d/m/Y') : 'N/A' }}
                                </p>
                                <p><strong>Fecha de Contratación:</strong>
                                    {{ $empleado->fecha_contratacion ? \Carbon\Carbon::parse($empleado->fecha_contratacion)->format('d/m/Y') : 'N/A' }}
                                </p>
                                <p><strong>Fecha de Conclusión:</strong>
                                    {{ $empleado->fecha_conclusion_contrato ? \Carbon\Carbon::parse($empleado->fecha_conclusion_contrato)->format('d/m/Y') : 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Documentos -->
                <div class="card mb-3">
                    <div class="card-header bg-secondary text-white">
                        <h5><i class="fas fa-file"></i> Documentos</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Curriculum:</strong>
                                    @if ($empleado->curriculum)
                                        <a href="{{ asset($empleado->curriculum) }}" target="_blank">Ver Currículum</a>
                                    @else
                                        N/A
                                    @endif
                                </p>
                                <p><strong>Contrato:</strong>
                                    @if ($empleado->contrato)
                                        <a href="{{ asset($empleado->contrato) }}" target="_blank">Ver Contrato</a>
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Contrato de Confidencialidad:</strong>
                                    @if ($empleado->contrato_confidencialidad)
                                        <a href="{{ asset($empleado->contrato_confidencialidad) }}" target="_blank">Ver Contrato de Confidencialidad</a>
                                    @else
                                        N/A
                                    @endif
                                </p>
                                <p><strong>Contrato de Consentimiento:</strong>
                                    @if ($empleado->contrato_consentimiento)
                                        <a href="{{ asset($empleado->contrato_consentimiento) }}" target="_blank">Ver Contrato de Consentimiento</a>
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rubros -->
                <div class="card mb-3">
                    <div class="card-header bg-secondary text-white">
                        <h5><i class="fas fa-list-ul"></i> Rubros Seleccionados</h5>
                    </div>
                    <div class="card-body">
                        <ul>
                            @foreach ($empleado->rubros as $rubro)
                                <li>{{ $rubro->nombre }} - Monto: {{ $rubro->pivot->monto }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Fechas -->
                <div class="card mb-3">
                    <div class="card-header bg-secondary text-white">
                        <h5><i class="fas fa-calendar"></i> Fechas</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Creación del Empleado:</strong>
                            {{ $empleado->created_at ? $empleado->created_at->format('d/m/Y H:i') : 'N/A' }}
                        </p>
                        <p><strong>Actualización del Empleado:</strong>
                            {{ $empleado->updated_at ? $empleado->updated_at->format('d/m/Y H:i') : 'N/A' }}
                        </p>
                    </div>
                </div>

                <!-- <div class="mt-3 text-center">
                    <a href="{{ route('empleados.indexEmpleados') }}" class="btn btn-primary">Volver al listado</a>
                </div> -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
