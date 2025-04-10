@extends('layouts.app')

@section('content')
    <div class="container mt-7">
            
            <h1 class="text-center mb-8">Listado de Cargos</h1>
    
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
    
            <div class="d-flex justify-content-between mb-3">
                <a href="{{ route('cargos.create') }}" class="btn btn-primary btn-lg">Crear Nuevo Cargo</a>
    
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped w-100 table-sm">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Nombre</th>
                            <th scope="col">Descripción</th>
                            <th scope="col">Codigo de Afiliacion</th>
                            <th scope="col">Salario Basico</th>
                            <th scope="col">Departamento</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
    
                    <tbody>
                        @foreach ($cargos as $cargo)
                            <tr>
                                <td>{{ $cargo->nombre_cargo }}</td>
                                <td>{{ $cargo->descripcion }}</td>
                                <td>{{ $cargo->codigo_afiliacion }}</td>
                                <td>{{ $cargo->salario_basico }}</td> 
                                <td>{{ $cargo->departamento->nombre }}</td>                               
                                <td class="text-center">
    
                                    <a href="{{ route('cargos.show', $cargo->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye "></i>
                                    </a>

                                    <a href="{{ route('cargos.edit', $cargo->id) }}"
                                        class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('cargos.destroy', $cargo->id) }}" method="POST"
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
        </div>
    </div>
@endsection
