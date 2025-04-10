@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header text-center bg-primary text-white">
                        <h1 class="mb-0">Detalles de Rubro</h1>
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th><i class="fas fa-hashtag"></i> ID</th>
                                    <td>{{ $rubro->id }}</td>
                                </tr>
                                <tr>
                                    <th><i class="fa-solid fa-box-archive"></i> Nombre del Rubro</th>
                                    <td>{{ $rubro->nombre }}</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-align-left"></i> Descripción</th>
                                    <td>{{ $rubro->descripcion }}</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-align-left"></i> Tipo de Rubro</th>
                                    <td>{{ $rubro->tipo_rubro }}</td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="mt-4 text-center">
                            <a href="{{ route('rubros.index') }}" class="btn btn-primary">Volver al listado</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


                
