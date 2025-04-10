@extends('layouts.app')

@section('content')
<div class="container mt-4" style="max-width: 700px;">
    <div class="card shadow">
        <div class="card-header bg-primary text-white text-center">
            <h1 class="mb-0">Editar Producto</h1>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('productos.update', $producto->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Campo Nombre -->
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $producto->nombre) }}" required>
                </div>

                <!-- Descripcion -->
                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <textarea name="descripcion" class="form-control" required>{{ old('descripcion', $producto->descripcion) }}</textarea>
                </div>

                <!-- Valor del Producto -->
                <div class="form-group mt-3">
                    <label for="valor">Valor del  Producto</label>
                    <input type="number" name="valor" class="form-control" value="{{ old('valor', $producto->valor_producto) }}">
                </div>


                <div class="d-flex justify-content-between mt-4">
                    <button type="submit" class="btn btn-primary">Guardar Producto</button>
                    <a href="{{ route('productos.index') }}" class="btn btn-outline-danger">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
