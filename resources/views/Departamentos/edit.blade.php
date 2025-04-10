@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card shadow-sm" style="max-width: 600px; margin: auto;">
            <div class="card-header bg-primary text-white text-center">
                <h1><i class="fas fa-edit"></i> Editar Departamento</h1>
            </div>

            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('departamentos.update', $departamento->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Campo Nombre -->
                    <div class="form-group mb-3">
                        <label for="nombre">Nombre</label>
                        <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $departamento->nombre) }}" required>
                    </div>

                    <!-- Campo Descripción -->
                    <div class="form-group mb-4">
                        <label for="descripcion">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="3" required>{{ old('descripcion', $departamento->descripcion) }}</textarea>
                    </div>

                    <!-- Botones Enviar -->
                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-success btn-md">
                            <i class="fas fa-save"></i> Guardar Cambios
                        </button>
                        <a href="{{ route('departamentos.index') }}" class="btn btn-secondary btn-md ms-2">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection