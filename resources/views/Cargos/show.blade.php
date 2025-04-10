@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header text-center bg-primary text-white">
                    <h1><i class="fas fa-info-circle"></i> Detalles del Cargo</h1>
                </div>


                <div class="card-body">
                    <table class="table table-bordered table-striped table-hover">
                        <tbody>
                            <tr>
                                <th><i class="fas fa-hashtag"></i> ID</th>
                                <td>{{ $cargo->id }}</td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-building"></i> Nombre</th>
                                <td>{{ $cargo->nombre_cargo }}</td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-align-left"></i> Descripción</th>
                                <td>{{ $cargo->descripcion }}</td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-hashtag"></i> Codigo de Afiliacion</th>
                                <td>{{ $cargo->codigo_afiliacion }}</td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-hashtag"></i> Salario Basico</th>
                                <td>{{ $cargo->salario_basico }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-center mt-4">
                        <a href="{{ route('cargos.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection