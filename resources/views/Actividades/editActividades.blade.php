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

                        <form action="{{ route('actividades.update', $actividades->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Grupo de información Cliente y Empleado -->
                            <fieldset class="border p-3 mb-4">
                                <legend class="text-primary"><i class="fas fa-users"></i> Información del Cliente y Empleado
                                </legend>

                                <div class="row">
                                    <!-- Campo Cliente -->
                                    <div class="form-group col-md-6">
                                        <label for="cliente_id">Clientes</label>
                                        <select name="cliente_id" id="cliente_id" class="form-select">
                                            <option value="">Seleccione un Cliente & Cooperativa</option>
                                            @foreach ($clientes as $cliente)
                                                <option value="{{ $cliente->id }}"
                                                    {{ $cliente->id == $actividades->cliente_id ? 'selected' : '' }}>
                                                    {{ $cliente->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Campo Empleado -->
                                    <div class="form-group col-md-6">
                                        <label for="empleado_id">Empleado</label>
                                        <select name="empleado_id" id="empleado_id" class="form-select" required>
                                            @foreach ($empleados as $empleado)
                                                <option value="{{ $empleado->id }}"
                                                    {{ $empleado->id == $actividades->empleado_id ? 'selected' : '' }}>
                                                    {{ $empleado->nombre1 }} {{ $empleado->apellido1 }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Departamento-->
                                    <div class="form-group col-md-6">
                                        <label for="departamento_id">Departamento</label>
                                        <select name="departamento_id" id="departamento_id" class="form-select" required>
                                            @foreach ($departamentos as $departamento)
                                                <option value="{{ $departamento->id }}"
                                                    {{ $departamento->id == $actividades->departamento_id ? 'selected' : '' }}>
                                                    {{ $departamento->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Campo Cargo -->
                                    <div class="form-group col-md-6">
                                        <label for="cargo_id">Cargo</label>
                                        <select name="cargo_id" id="cargo_id" class="form-select" required>
                                            @foreach ($cargos as $cargo)
                                                <option value="{{ $cargo->id }}"
                                                    {{ $cargo->id == $actividades->cargo_id ? 'selected' : '' }}>
                                                    {{ $cargo->nombre_cargo }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!-- Campo Producto -->
                                    <div class="form-group mb-4">
                                        <label for="producto_id">Producto</label>
                                        <select name="producto_id" id="producto_id" class="form-control" required>
                                            <option value="">Seleccione un producto</option>
                                            @foreach ($productos as $producto)
                                                <option value="{{ $producto->id }}"
                                                    {{ old('producto_id', $actividad->producto_id ?? '') == $producto->id ? 'selected' : '' }}>
                                                    {{ $producto->codigo . ' - ' . $producto->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('producto_id'))
                                            <small class="text-danger">{{ $errors->first('producto_id') }}</small>
                                        @endif
                                    </div>    
                                </div>
                            </fieldset>

                            <!-- Grupo de información de Actividad -->
                            <fieldset class="border p-3 mb-4">
                                <legend class="text-primary"><i class="fas fa-tasks"></i> Detalles de la Actividad</legend>

                                <div class="row">
                                    <!-- Campo Descripción -->
                                    <div class="form-group col-md-6">
                                        <label for="descripcion">Descripción</label>
                                        <textarea name="descripcion" class="form-control">{{ old('descripcion', $actividades->descripcion) }}</textarea>
                                    </div>

                                    <!-- Campo Código OSTicket -->
                                    <div class="form-group col-md-6">
                                        <label for="codigo_osticket">Código Osticket</label>
                                        <input type="text" name="codigo_osticket" class="form-control"
                                            value="{{ old('codigo_osticket', $actividades->codigo_osticket) }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Campo Frecuencia -->
                                    <div class="form-group col-md-6">
                                        <label for="semanal_diaria">Frecuencia</label>
                                        <select name="semanal_diaria" class="form-select" required>
                                            <option value="SEMANAL"
                                                {{ old('semanal_diaria', $actividades->semanal_diaria) == 'SEMANAL' ? 'selected' : '' }}>
                                                Semanal</option>
                                            <option value="DIARIO"
                                                {{ old('semanal_diaria', $actividades->semanal_diaria) == 'DIARIO' ? 'selected' : '' }}>
                                                Diario</option>
                                        </select>
                                    </div>

                                    <!-- Campo Estado -->
                                    <div class="form-group col-md-6">
                                        <label for="estado">Estado</label>
                                        <select name="estado" class="form-select" required>
                                            <option value="EN CURSO"
                                                {{ old('estado', $actividades->estado) == 'EN CURSO' ? 'selected' : '' }}>
                                                En Curso</option>
                                            <option value="FINALIZADO"
                                                {{ old('estado', $actividades->estado) == 'FINALIZADO' ? 'selected' : '' }}>
                                                Finalizado</option>
                                            <option value="PENDIENTE"
                                                {{ old('estado', $actividades->estado) == 'PENDIENTE' ? 'selected' : '' }}>
                                                Pendiente</option>
                                        </select>
                                    </div>
                                </div>
                            </fieldset>

                            <!-- Grupo de información de Fechas y Tiempo -->
                            <fieldset class="border p-3 mb-4">
                                <legend class="text-primary"><i class="fas fa-calendar-alt"></i> Fechas y Tiempo Estimado
                                </legend>

                                <div class="row">
                                    <!-- Campo Fecha de Inicio -->
                                    <div class="form-group col-md-6">
                                        <label for="fecha_inicio">Fecha de Inicio</label>
                                        <input type="date" name="fecha_inicio" class="form-control"
                                            value="{{ old('fecha_inicio', $actividades->fecha_inicio->format('Y-m-d')) }}"
                                            required>
                                    </div>

                                    <!-- Campo Fecha de Fin -->
                                    <div class="form-group col-md-6">
                                        <label for="fecha_fin">Fecha de Fin</label>
                                        <input type="date" name="fecha_fin" class="form-control"
                                            value="{{ old('fecha_fin', $actividades->fecha_fin ? $actividades->fecha_fin->format('Y-m-d') : '') }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Campo Tiempo Estimado -->
                                    <div class="form-group col-md-6">
                                        <label for="tiempo_estimado">Tiempo Estimado (minutos)</label>
                                        <input type="number" name="tiempo_estimado" class="form-control"
                                            value="{{ old('tiempo_estimado', $actividades->tiempo_estimado) }}" required>
                                    </div>

                                    <!-- Campo Avance -->
                                    <div class="form-group col-md-6">
                                        <label for="avance">Avance (%)</label>
                                        <input type="number" name="avance" class="form-control"
                                            value="{{ old('avance', $actividades->avance) }}" min="0" max="100"
                                            required>
                                    </div>
                                </div>
                            </fieldset>

                            <!-- Grupo de información adicional -->
                            <fieldset class="border p-3 mb-4">
                                <legend class="text-primary"><i class="fas fa-info-circle"></i> Información Adicional
                                </legend>

                                <div class="row">
                                    <!-- Campo Observaciones -->
                                    <div class="form-group col-md-6">
                                        <label for="observaciones">Observaciones</label>
                                        <textarea name="observaciones" class="form-control">{{ old('observaciones', $actividades->observaciones) }}</textarea>
                                    </div>

                                    <!-- Campo Prioridad -->
                                    <div class="form-group col-md-6">
                                        <label for="prioridad">Prioridad</label>
                                        <select name="prioridad" class="form-select" required>
                                            <option value="ALTA"
                                                {{ old('prioridad', $actividades->prioridad) == 'ALTA' ? 'selected' : '' }}>
                                                Alta</option>
                                            <option value="MEDIA"
                                                {{ old('prioridad', $actividades->prioridad) == 'MEDIA' ? 'selected' : '' }}>
                                                Media</option>
                                            <option value="BAJA"
                                                {{ old('prioridad', $actividades->prioridad) == 'BAJA' ? 'selected' : '' }}>
                                                Baja</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Campo Repetitivo -->
                                    <div class="form-group col-md-6">
                                        <label for="repetitivo">Repetitivo</label>
                                        <select name="repetitivo" class="form-select" required>
                                            <option value="1"
                                                {{ old('repetitivo', $actividades->repetitivo) == '1' ? 'selected' : '' }}>
                                                Sí</option>
                                            <option value="0"
                                                {{ old('repetitivo', $actividades->repetitivo) == '0' ? 'selected' : '' }}>
                                                No</option>
                                        </select>
                                    </div>

                                    <!-- Campo Tipo de Error -->
                                    <div class="form-group col-md-6">
                                        <label for="error">Tipo de Error</label>
                                        <select name="error" class="form-select" required>
                                            <option value="CLIENTE"
                                                {{ old('error', $actividades->error) == 'CLIENTE' ? 'selected' : '' }}>
                                                Cliente</option>
                                            <option value="SOFTWARE"
                                                {{ old('error', $actividades->error) == 'SOFTWARE' ? 'selected' : '' }}>
                                                Software</option>
                                            <option value="MEJORA ERROR"
                                                {{ old('error', $actividades->error) == 'MEJORA ERROR' ? 'selected' : '' }}>
                                                Mejora Error</option>
                                            <option value="DESARROLLO"
                                                {{ old('error', $actividades->error) == 'DESARROLLO' ? 'selected' : '' }}>
                                                Desarrollo</option>
                                            <option value="OTRO"
                                                {{ old('error', $actividades->error) == 'OTRO' ? 'selected' : '' }}>
                                                Otros</option>
                                        </select>
                                    </div>
                                </div>
                            </fieldset>

                            <!-- Botones de acción -->
                            <div class="text-end">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Actualizar
                                </button>
                                <a href="{{ route('actividades.indexActividades') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Cancelar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
