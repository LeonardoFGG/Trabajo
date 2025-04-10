@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header text-center bg-primary text-white">
                        <h1 class="mb-0">Detalles del Daily Scrum</h1>
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th><i class="fas fa-hashtag"></i> ID</th>
                                    <td>{{ $daily->id }}</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-calendar-alt"></i> Fecha</th>
                                    <td>{{ \Carbon\Carbon::parse($daily->fecha)->format('d-m-Y') }}</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-user"></i> Empleado</th>
                                    <td>{{ $daily->empleado->nombre1 }} {{ $daily->empleado->apellido1 }}</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-list"></i> Ayer</th>
                                    <td>{{ $daily->ayer }}</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-list"></i> Hoy</th>
                                    <td>{{ $daily->hoy }}</td>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-list"></i> Dificultades</th>
                                    <td>{{ $daily->dificultades }}</td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="mt-4 text-center">
                            <a href="{{ route('daily.index') }}">Volver a la lista</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection