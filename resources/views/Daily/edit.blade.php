@extends('layouts.app')

@section('content')
    <div class="container mt-4" style="max-width: 700px;">
        <div class="card shadow">
            <div class="card-header bg-primary text-white text-center">
                <h1 class="mb-0">Editar El Daily Scrum</h1>
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

                <form action="{{ route('daily.update', $daily->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Campo Empleado -->
                    <div class="form-group mt-4">
                        <label for="empleado_id">Empleado <span class="text-danger">*</span></label>
                        <select name="empleado_id" class="form-control" required>
                            <option value="">Seleccione un empleado</option>
                            @foreach ($empleados as $empleado)
                                <option value="{{ $empleado->id }}"
                                    {{ old('empleado_id', $daily->empleado_id) == $empleado->id ? 'selected' : '' }}>
                                    {{ $empleado->nombre1 }} {{ $empleado->apellido1 }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Campo Ayer -->
                    <div class="form-group mt-4">
                        <label for="ayer">Ayer <span class="text-danger">*</span></label>
                        <textarea name="ayer" class="form-control" required>{{ old('ayer', $daily->ayer) }}</textarea>
                    </div>

                    <!-- Campo Hoy -->
                    <div class="form-group mt-4">
                        <label for="hoy">Hoy <span class="text-danger">*</span></label>
                        <textarea name="hoy" class="form-control" required>{{ old('hoy', $daily->hoy) }}</textarea>
                    </div>

                    <!-- Campo Dificultades --> 
                    <div class="form-group
                        mt-4">
                        <label for="dificultades">Dificultades <span class="text-danger">*</span></label>
                        <textarea name="dificultades" class="form-control"
                            required>{{ old('dificultades', $daily->dificultades) }}</textarea>
                    </div>

                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <a href="{{ route('daily.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection