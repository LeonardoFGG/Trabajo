@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h1 class="text-center mb-4">Listado de Rubros</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-3">
            <a href="{{ route('rubros.create') }}" class="btn btn-primary btn-lg">Crear Rubro</a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Descripción</th>
                        <th scope="col">Tipo de Rubro</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rubros as $rubro)
                        <tr>
                            <td>{{ $rubro->id }}</td>
                            <td>{{ $rubro->nombre }}</td>
                            <td>{{ $rubro->descripcion }}</td>
                            <td>{{ $rubro->tipo_rubro }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('rubros.show', $rubro->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye
                                    "></i>
                                </a>

                                <a href="{{ route('rubros.edit', $rubro->id) }}"
                                    class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('rubros.destroy', $rubro->id) }}" method="POST"
                                    class="d-inline form-delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm btn-delete" title="Eliminar">
                                        <i class="fas fa-trash fa-md"></i>
                                    </button>
                                </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- Paginación -->
        <div class="d-flex justify-content-center my-4">
            {{ $rubros->links('pagination::bootstrap-4') }}
        </div>

    </div>
@endsection