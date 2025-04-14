@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white text-center">
                        <h1><i class="fas fa-info-circle"></i> Detalles de la Actividad</h1>
                    </div>
                    <div class="card-body">
                        @include('Actividades.partials.show-content', ['actividades' => $actividades])
                        <div class="mt-3 text-center">
                            <a href="{{ route('actividades.indexActividades') }}" class="btn btn-primary">Volver al listado</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
