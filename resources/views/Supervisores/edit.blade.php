@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header text-center bg-primary text-white">
                    <h1><i class="fas fa-edit"></i> Editar Supervisor</h1>
                </div>

                <div class="card-body">
                    <form action="{{ route('supervisores.update', $supervisor->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="nombre_supervisor"><i class="fas fa-user-tie"></i> Nombre del Supervisor</label>
                            <input type="text" class="form-control" id="nombre_supervisor" name="nombre_supervisor" value="{{ old('nombre_supervisor', $supervisor->nombre_supervisor) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="empleado_id"><i class="fas fa-user"></i> Empleado</label>
                            <select class="form-control" id="empleado_id" name="empleado_id" required>
                                @foreach($empleados as $empleado)
                                    <option value="{{ $empleado->id }}" {{ $empleado->id == $supervisor->empleado_id ? 'selected' : '' }}>
                                        {{ $empleado->nombre1  }}  {{ $empleado->apellido1 }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="departamento_id"><i class="fas fa-building"></i> Departamento</label>
                            <select class="form-control" id="departamento_id" name="departamento_id" required>
                                @foreach($departamentos as $departamento)
                                    <option value="{{ $departamento->id }}" {{ $departamento->id == $supervisor->departamento_id ? 'selected' : '' }}>
                                        {{ $departamento->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="supervisor_id"><i class="fas fa-user-shield"></i> Supervisor Superior</label>
                            <select class="form-control" id="supervisor_id" name="supervisor_id">
                                <option value="">Ninguno</option>
                                @foreach($supervisores as $sup)
                                    <option value="{{ $sup->id }}" {{ $sup->id == $supervisor->supervisor_id ? 'selected' : '' }}>
                                        {{ $sup->nombre_supervisor }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar cambios
                            </button>
                            <a href="{{ route('supervisores.index') }}" class="btn btn-secondary ml-2">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


                    
