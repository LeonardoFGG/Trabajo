@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="card shadow-sm" style="max-width: 600px; margin: auto;">
            <div class="card-header text-center bg-primary text-white">
                <h2><i class="fa-solid fa-building"></i> Crear Nuevo Supervisor</h2>
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

                <form action="{{ route('supervisores.store') }}" method="POST">
                    @csrf

                    <!-- Nombre Supervisor -->
                    <div class="form-group mb-3">
                        <label for="nombre_supervisor">Nombre</label>
                        <input type="text" name="nombre_supervisor" id="nombre_supervisor" class="form-control" value="{{ old('nombre_supervisor') }}">
                    </div>

                    <!-- Seleccionar Empleado -->
                    <div class="form-group mb-3">
                        <label for="empleado_id">Empleado</label>
                        <select name="empleado_id" id="empleado_id" class="form-control">
                            <option value="">Seleccione un empleado</option>
                            @foreach ($empleados as $empleado)
                                <option value="{{ $empleado->id }}" {{ old('empleado_id') == $empleado->id ? 'selected' : '' }}>
                                    {{ $empleado->nombre1 }} {{$empleado->apellido1}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Seleccionar Departamento -->
                    <div class="form-group mb-3">
                        <label for="departamento_id">Departamento</label>
                        <select name="departamento_id" id="departamento_id" class="form-control">
                            <option value="">Seleccione un departamento</option>
                            @foreach ($departamentos as $departamento)
                                <option value="{{ $departamento->id }}" {{ old('departamento_id') == $departamento->id ? 'selected' : '' }}>
                                    {{ $departamento->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Seleccionar Supervisor Superior -->
                    <div class="form-group mb-3">
                        <label for="supervisor_id">Supervisor Superior</label>
                        <select name="supervisor_id" id="supervisor_id" class="form-control">
                            <option value="">Seleccione un supervisor superior (opcional)</option>
                            @foreach ($supervisores as $supervisor)
                                <option value="{{ $supervisor->id }}" {{ old('supervisor_id') == $supervisor->id ? 'selected' : '' }}>
                                    {{ $supervisor->nombre_supervisor }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Botones Enviar -->
                    <div class="form-group text-center mt-4">
                        <button type="submit" class="btn btn-success me-2">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                        <a href="{{ route('supervisores.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

