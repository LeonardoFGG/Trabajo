@extends('layouts.app')

@section('content')
    <div class="container mt-4" style="max-width: 700px;">
        <div class="card shadow">
            <div class="card-header bg-primary text-white text-center">
                <h1 class="mb-0">Editar Rol de Pago del Empleado</h1>
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

                <form action="{{ route('roles_pago.update', $roles_pago->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Campo Empleado -->
                    <div class="form-group mt-4">
                        <label for="empleado_id">Empleado <span class="text-danger">*</span></label>
                        <select name="empleado_id" class="form-control" required>
                            <option value="">Seleccione un empleado</option>
                            @foreach ($empleados as $empleado)
                                <option value="{{ $empleado->id }}"
                                    {{ old('empleado_id', $roles_pago->empleado_id) == $empleado->id ? 'selected' : '' }}>
                                    {{ $empleado->nombre }} {{ $empleado->apellido }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Seleccionar los rubros para el empleado -->
                    <div class="form-group mt-4">
                        <label for="rubros">Selecciona Rubros</label>
                        <div id="rubros">
                            @foreach ($rubros as $rubro)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="rubros[]"
                                        id="rubro{{ $rubro->id }}" value="{{ $rubro->id }}"
                                        @if (in_array($rubro->id, $roles_pago->rubros->pluck('id')->toArray())) checked @endif>
                                    <label class="form-check-label" for="rubro{{ $rubro->id }}">
                                        {{ $rubro->nombre }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

            </div>

            <!-- Campo Fecha de Inicio -->
            <div class="form-group mt-4">
                <label for="fecha_inicio">Fecha de Inicio <span class="text-danger">*</span></label>
                <input type="date" name="fecha_inicio" class="form-control"
                    value="{{ old('fecha_inicio', $roles_pago->fecha_inicio) }}" required>
            </div>

            <!-- Campo Fecha de Fin -->
            <div class="form-group mt-4">
                <label for="fecha_fin">Fecha de Fin <span class="text-danger">*</span></label>
                <input type="date" name="fecha_fin" class="form-control"
                    value="{{ old('fecha_fin', $roles_pago->fecha_fin) }}" required>
            </div>

            <!-- Campo Total_Ingreso -->
            <div class="form-group mt-4">
                <label for="total_ingreso">Total de Ingreso <span class="text-danger">*</span></label>
                <input type="number" name="total_ingreso" class="form-control"
                    value="{{ old('total_ingreso', $roles_pago->total_ingreso) }}" required>
            </div>

            <!-- Campo Total_Egreso -->
            <div class="form-group mt-4">
                <label for="total_egreso">Total de Egreso <span class="text-danger">*</span></label>
                <input type="number" name="total_egreso" class="form-control"
                    value="{{ old('total_egreso', $roles_pago->total_egreso) }}" required>
            </div>

            <!-- Campo Salario Neto -->
            <div class="form-group mt-4">
                <label for="salario_neto">Salario Neto <span class="text-danger">*</span></label>
                <input type="number" name="salario_neto" class="form-control"
                    value="{{ old('salario_neto', $roles_pago->salario_neto) }}" required>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <button type="submit" class="btn btn-primary">Guardar Rol de Pago</button>
                <a href="{{ route('roles_pago.index') }}" class="btn btn-outline-danger">Cancelar</a>
            </div>

            </form>
        </div>
    </div>
    </div>
@endsection
