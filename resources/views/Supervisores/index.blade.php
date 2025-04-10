@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-center">Listado de Supervisores</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('supervisores.create') }}" class="btn btn-primary">Crear Nuevo Supervisor</a>

        <table class="table mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Empleados</th>
                    <th>Departamento</th>
                    <th>Supervisor Superior</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($supervisores as $supervisor)
                    <tr>
                        <td>{{ $supervisor->id }}</td>
                        <td>{{ $supervisor->nombre_supervisor }}</td>
                        <td>
                            @if ($supervisor->empleados->isEmpty())
                                No tiene empleados
                            @else
                                <ul>
                                    @foreach ($supervisor->empleados as $empleado)
                                        <li>{{ $empleado->nombre1 }} {{ $empleado->apellido1 }}</li> <!-- Muestra el nombre completo del empleado -->
                                    @endforeach
                                </ul>
                            @endif
                        </td>
                        <td>{{ $supervisor->departamento->nombre }}</td>

                        <!-- Mostrar el supervisor superior si tiene un supervisor_id -->
                        <td>
                            @if ($supervisor->supervisor_id)
                                <!-- Si tiene supervisor, muestra el nombre del supervisor superior -->
                                {{ $supervisor->supervisor ? $supervisor->supervisor->nombre_supervisor : 'No disponible' }}
                            @else
                                <!-- Si no tiene supervisor, muestra que es supervisor superior -->
                                <span>Supervisor Superior</span>
                            @endif
                        </td>

                        <td>
                            <a href="{{ route('supervisores.show', $supervisor->id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>

                            <a href="{{ route('supervisores.edit', $supervisor->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('supervisores.destroy', $supervisor->id) }}" method="POST" class="d-inline form-delete">
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


