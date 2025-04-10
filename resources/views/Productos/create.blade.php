@extends('layouts.app')

@section('content')
    <div class="container mt-4" style="max-width: 700px;">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h1 class="mb-0">Crear Nuevo Producto</h1>
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

                <form action="{{ route('productos.store') }}" method="POST">
                    @csrf

                    <!-- Campo Nombre -->
                    <div class="form-group">
                        <label for="nombre">Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" class="form-control"
                            placeholder="Ingrese un nombre del Producto" value="{{ old('nombre') }}" required>
                    </div>

                    <!-- Campo Descripción -->
                    <div class="form-group">
                        <label for="descripcion">Descripción <span class="text-danger">*</span></label>
                        <textarea name="descripcion" class="form-control" placeholder="Describe la actividad">{{ old('descripcion') }}</textarea>
                    </div>

                    <!-- Valor Producto -->
                    <div class="form-group mt-3">
                        <label for="valor_producto">Valor del Producto <span class="text-danger">*</span></label>
                        <input type="number" name="valor_producto" class="form-control"
                            placeholder="Ingrese el valor del Producto" value="{{ old('valor_producto') }}" >
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
