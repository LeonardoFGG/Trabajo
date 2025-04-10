@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header text-center bg-primary text-white">
                    <h1><i class="fas fa-info-circle"></i> Detalles del Departamento</h1>
                </div>

                <div class="card-body">
                    <table class="table table-bordered table-striped table-hover">
                        <tbody>
                            <tr>
                                <th><i class="fas fa-hashtag"></i> ID</th>
                                <td>{{ $departamento->id }}</td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-building"></i> Nombre</th>
                                <td>{{ $departamento->nombre }}</td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-align-left"></i> Descripción</th>
                                <td>{{ $departamento->descripcion }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-center mt-4">
                        <a href="{{ route('departamentos.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
