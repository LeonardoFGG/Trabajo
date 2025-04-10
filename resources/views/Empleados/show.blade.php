@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white text-center">
                        <h1><i class="fas fa-info-circle"></i> Detalles del Empleado</h1>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Columna 1: Información Básica -->
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-header bg-secondary text-white">
                                        <h5><i class="fas fa-user"></i> Información Básica</h5>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>ID:</strong> {{ $empleados->id }}</p>
                                        <p><strong>Nombres:</strong> {{ $empleados->nombre1 . ' ' . $empleados->nombre2 }}</p>
                                        <p><strong>Apellidos:</strong> {{ $empleados->apellido1 . ' ' . $empleados->apellido2 }}</p>
                                        <p><strong>DUI:</strong> {{ $empleados->cedula }}</p>
                                        <p><strong>Fecha de Nacimiento:</strong> {{ $empleados->fecha_nacimiento }}</p>
                                        <p><strong>Teléfono:</strong> {{ $empleados->telefono }}</p>
                                        <p><strong>Celular:</strong> {{ $empleados->celular }}</p>
                                        <p><strong>Correo Institucional:</strong> {{ $empleados->correo_institucional }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Columna 2: Información Laboral -->
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-header bg-secondary text-white">
                                        <h5><i class="fas fa-briefcase"></i> Información Laboral</h5>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Departamento:</strong> {{ $empleados->departamento->nombre }}</p>
                                        <p><strong>Cargo:</strong> {{ $empleados->cargo->nombre_cargo }}</p>
                                        <p><strong>Supervisor:</strong>
                                            @if ($empleados->supervisor)
                                                {{ $empleados->supervisor->nombre_supervisor }}
                                            @else
                                                Supervisor Superior
                                            @endif
                                        </p>
                                        <p><strong>Tipo de Jornada:</strong> {{ $empleados->jornada_laboral }}</p>
                                        <p><strong>Fecha de Ingreso:</strong>
                                            {{ $empleados->fecha_ingreso ? \Carbon\Carbon::parse($empleados->fecha_ingreso)->format('d/m/Y') : 'N/A' }}
                                        </p>
                                        <p><strong>Fecha de Contratación:</strong>
                                            {{ $empleados->fecha_contratacion ? \Carbon\Carbon::parse($empleados->fecha_contratacion)->format('d/m/Y') : 'N/A' }}
                                        </p>
                                        <p><strong>Fecha de Conclusión:</strong>
                                            {{ $empleados->fecha_conclusion_contrato ? \Carbon\Carbon::parse($empleados->fecha_conclusion_contrato)->format('d/m/Y') : 'N/A' }}
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
                                            @if ($empleados->curriculum)
                                                <a href="{{ asset('storage/' . $empleados->curriculum) }}" target="_blank">Ver Curriculum</a>
                                            @else
                                                N/A
                                            @endif
                                        </p>
                                        <p><strong>Contrato:</strong>
                                            @if ($empleados->contrato)
                                                <a href="{{ asset('storage/' . $empleados->contrato) }}" target="_blank">Ver Contrato</a>
                                            @else
                                                N/A
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Contrato de Confidencialidad:</strong>
                                            @if ($empleados->contrato_confidencialidad)
                                                <a href="{{ asset('storage/' . $empleados->contrato_confidencialidad) }}" target="_blank">Ver Contrato</a>
                                            @else
                                                N/A
                                            @endif
                                        </p>
                                        <p><strong>Contrato de Consentimiento:</strong>
                                            @if ($empleados->contrato_consentimiento)
                                                <a href="{{ asset('storage/' . $empleados->contrato_consentimiento) }}" target="_blank">Ver Contrato</a>
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
                                    @foreach ($empleados->rubros as $rubro)
                                        <li>{{ $rubro->nombre }} - Monto: {{ $rubro->pivot->monto }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <!-- Fechas de Creación y Actualización -->
                        <div class="card mb-3">
                            <div class="card-header bg-secondary text-white">
                                <h5><i class="fas fa-calendar"></i> Fechas</h5>
                            </div>
                            <div class="card-body">
                                <p><strong>Creación del Empleado:</strong>
                                    @if ($empleados->created_at)
                                        {{ $empleados->created_at->format('d/m/Y H:i') }}
                                    @else
                                        N/A
                                    @endif
                                </p>
                                <p><strong>Actualización del Empleado:</strong>
                                    @if ($empleados->updated_at)
                                        {{ $empleados->updated_at->format('d/m/Y H:i') }}
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- Botón de Volver -->
                        <div class="mt-3 text-center">
                            <a href="{{ route('empleados.indexEmpleados') }}" class="btn btn-primary">Volver al listado</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection