@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header text-center bg-primary text-white">
                        <h1 class="mb-0">Detalles de Rol de Pago</h1>
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th><i class="fas fa-hashtag"></i> ID</th>
                                    <td>{{ $rolespago->id }}</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-user"></i> Empleado</th>
                                    <td>{{ $rolespago->empleado->nombre }} {{ $rolespago->empleado->apellido }}</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-calendar-alt"></i> Fecha de Inicio</th>
                                    <td>{{ $rolespago->fecha_inicio }}</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-calendar-alt"></i> Fecha de Fin</th>
                                    <td>{{ $rolespago->fecha_fin }}</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-list"></i> Rubros</th>
                                    <td>
                                        <ul class="mb-0">
                                            @foreach ($rolespago->rubros as $rubro)
                                                <li>{{ $rubro->nombre }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="mt-4 text-center">
                            <a href="{{ route('roles_pago.index') }}">Volver a la lista</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
