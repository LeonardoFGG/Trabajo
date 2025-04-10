@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card shadow-sm" style="max-width: 600px; margin: auto;">
            <div class="card-header bg-primary text-white text-center">
                <h1><i class="fas fa-edit"></i> Editar Cargo</h1>
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

                <form action="{{ route('cargos.update', $cargo->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Campo Nombre -->
                    <div class="form-group mb-3">
                        <label for="nombre">Nombre</label>
                        <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $cargo->nombre_cargo) }}">
                    </div>

                    <!-- Campo Descripción -->
                    <div class="form-group mb-4">
                        <label for="descripcion">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="3" required>{{ old('descripcion', $cargo->descripcion) }}</textarea>
                    </div>

		<!-- Departamento-->
                    <div class="form-group mb-3">
                        <label for="departamento_id">Departamento</label>
                        <select name="departamento_id" class="form-control" required>
                            <option value="">Seleccione un Departamento</option>
                            @foreach ($departamentos as $departamento)
                                <option value="{{ $departamento->id }}" {{ old('departamento_id', $cargo->departamento_id) == $departamento->id ? 'selected' : '' }}>
                                    {{ $departamento->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Codigo de Afiliacion -->
                    <div class="form-group mb-3">
                        <label for="codigo_afiliacion">Codigo de Afiliacion</label>
                        <input type="text" name="codigo_afiliacion" class="form-control" value="{{ old('codigo_afiliacion', $cargo->codigo_afiliacion) }}" required>
                    </div>

                    <!-- Salario Basico -->
                    <div class="form-group mb-3">
                        <label for="salario_basico">Salario Basico</label>
                        <input type="text" name="salario_basico" class="form-control" value="{{ old('salario_basico', $cargo->salario_basico) }}" required>
                    </div>

                    <!-- Botones Enviar -->
                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-success btn-md">
                            <i class="fas fa-save"></i> Guardar Cambios
                        </button>
                        <a href="{{ route('cargos.index') }}" class="btn btn-secondary btn-md ms-2">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

