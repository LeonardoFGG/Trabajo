@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header text-center bg-primary text-white">
                    <h1><i class="fas fa-info-circle"></i> Detalles del Supervisor</h1>
                </div>

                <div class="card-body">
                    <table class="table table-bordered table-striped table-hover">
                        <tbody>
                            <tr>
                                <th><i class="fas fa-hashtag"></i> ID</th>
                                <td>{{ $supervisor->id }}</td>
                            </tr>

                            <tr>
                                <th><i class="fas fa-user-tie"></i> Supervisor</th>
                                <td>{{ $supervisor->nombre_supervisor }}</td>
                            </tr>
                            
                            <tr>
                                <th><i class="fas fa-align-left"></i> Departamento</th>
                                <td>{{ $supervisor->departamento->nombre }}</td>
                            </tr>

                            <!-- Mostrar si el supervisor es un "Supervisor Superior" -->
                            <tr>
                                <th><i class="fas fa-shield-alt"></i> Tipo de Supervisor</th>
                                <td>
                                    @if($supervisor->supervisor_id)
                                        Supervisor
                                    @else
                                        Supervisor Superior
                                    @endif
                                </td>
                            </tr>

                            <!-- Mostrar el Supervisor Superior si existe -->
                            @if($supervisor->supervisor_id)
                            <tr>
                                <th><i class="fas fa-user-shield"></i> Supervisor Superior</th>
                                <td>{{ $supervisor->supervisor->nombre_supervisor ?? 'N/A' }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-center mt-4">
                        <a href="{{ route('supervisores.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
