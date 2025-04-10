@extends('layouts.app')

@section('content')
    <div class="container mt-7">
        <h1 class="text-center mb-4">Lista de Parametro</h1>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <a href="{{ route('parametros.create') }}" class="btn btn-primary">Añadir Parametro</a>

        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre del Parámetro</th>
                    <th>Departamento</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($parametros as $parametro)
                    <tr>
                        <td>{{ $parametro->id }}</td>
                        <td>{{ $parametro->nombre }}</td>
                         <td>
                            {{ $parametro->departamento->nombre ?? 'Sin departamento' }}
                        </td>
                        <td>
			  <a href="{{ route('parametros.show', $parametro->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye
                                    "></i>
                                </a>

                                <a href="{{ route('parametros.edit', $parametro->id) }}"
                                    class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('parametros.destroy', $parametro->id) }}" method="POST"
                                    class="d-inline form-delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm btn-delete" title="Eliminar">
                                        <i class="fas fa-trash fa-md"></i>
                                    </button>
                                </form>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection