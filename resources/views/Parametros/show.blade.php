@extends('layouts.app')

@section('content')
    <div class="container mt-4" style="max-width: 700px;">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Detalles del Parametro</h4>
		</div>


                <div class="card-body">
                    <table class="table table-bordered table-striped table-hover">
                        <tbody>
                            <tr>
                                <th><i class="fas fa-hashtag"></i> ID</th>
                                <td>{{ $parametro->id }}</td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-building"></i> Nombre</th>
                                <td>{{ $parametro->nombre }}</td>
                            </tr>
                            <tr>
                                <th><i class="fas fa-align-left"></i> Departamento</th>
                                <td>{{ $parametro->departamento->nombre ?? 'No asignado' }}</td>
                            </tr>
                            
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-center mt-4">
                        <a href="{{ route('parametros.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
