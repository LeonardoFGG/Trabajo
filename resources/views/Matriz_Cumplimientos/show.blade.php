@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="text-center mb-4">Detalle de Cumplimiento</h1>

    <div class="card shadow p-4">
        <div class="row mb-3">
            <div class="col-md-6">
                <h5><strong>Parámetro:</strong></h5>
                <p>{{ $matriz_cumplimiento->parametro->nombre ?? 'No asignado' }}</p>
            </div>
            <div class="col-md-6">
                <h5><strong>Puntos:</strong></h5>
                <p>{{ $matriz_cumplimiento->puntos }}</p>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <h5><strong>Empleado:</strong></h5>
                <p>{{ $matriz_cumplimiento->empleado->nombre1 ?? 'No asignado' }}</p>
            </div>
            <div class="col-md-6">
                <h5><strong>Cargo:</strong></h5>
                <p>{{ $matriz_cumplimiento->cargo->nombre_cargo ?? 'No asignado' }}</p>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <h5><strong>Supervisor:</strong></h5>
                <p>{{ $matriz_cumplimiento->supervisor->nombre_supervisor ?? 'Sin supervisor' }}</p>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('matriz_cumplimientos.index') }}" class="btn btn-secondary">Volver</a>
        
    </div>
</div>
@endsection

