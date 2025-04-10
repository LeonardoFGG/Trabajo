@extends('layouts.app')

@section('content')
    <div class="container mt-4" style="max-width: 700px;">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h1 class="mb-0">Crear Nuevo Registro Diario</h1>
            </div>

            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('daily.store') }}" method="POST">
                    @csrf
                    
                    <!-- Campo Empleado -->
                    <div class="form-group mt-4">
                        <label for="empleado_id">Empleado <span class="text-danger">*</span></label>
                        <select name="empleado_id" class="form-control" required>
                            <option value="">Seleccione un empleado</option>
                            @foreach ($empleados as $empleado)
                                <option value="{{ $empleado->id }}"
                                    {{ old('empleado_id') == $empleado->id ? 'selected' : '' }}>
                                    {{ $empleado->nombre1 }} {{ $empleado->apellido1 }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group mt-4">
                        <label for="fecha">Fecha</label>
                        <input type="text" name="fecha" class="form-control" value="{{ date('d-m-Y') }}" readonly>
                    </div>

                    <!-- Ayer que hizo -->
                    <div class="form-group mt-4">
                        <label for="ayer">¿Ayer que hizo? <span class="text-danger">*</span></label>
                        <textarea name="ayer" class="form-control" rows="3" required>{{ old('ayer') }}</textarea>
                    </div>

                    <!-- Hoy que hara -->
                    <div class="form-group mt-4">
                        <label for="hoy">¿Hoy que hara? <span class="text-danger">*</span></label>
                        <textarea name="hoy" class="form-control" rows="3" required>{{ old('hoy') }}</textarea>
                    </div>

                    <!-- Dificultades -->
                    <div class="form-group mt-4">
                        <label for="dificultades">Dificultades <span class="text-danger">*</span></label>
                        <textarea name="dificultades" class="form-control" rows="3" required>{{ old('dificultades') }}</textarea>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <button type="submit" class="btn btn-primary">Guardar </button>
                        <a href="{{ route('daily.index') }}">Volver a la lista</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
