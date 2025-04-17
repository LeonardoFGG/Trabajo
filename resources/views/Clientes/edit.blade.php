@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-7">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white text-center">
                        <h1><i class="fa-solid fa-user-tie"></i> Editar Cliente</h1>
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

                        <form action="{{ route('clientes.update', $cliente->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <fieldset class="border p-3 mb-4">
                                <legend class="text-primary"><i class="fa-solid fa-user-tie"></i> Información del Cliente
                                </legend>
                                <div class="row">
                                    <!-- Campos de información del cliente (nombre, dirección, etc.) -->
                                    <div class="form-group col-md-6">
                                        <label for="nombre">Nombre</label>
                                        <input type="text" name="nombre" class="form-control"
                                            value="{{ old('nombre', $cliente->nombre) }}" required>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="direccion">Dirección</label>
                                        <input type="text" name="direccion" class="form-control"
                                            value="{{ old('direccion', $cliente->direccion) }}">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="telefono">Teléfono</label>
                                        <input type="text" name="telefono" class="form-control"
                                            value="{{ old('telefono', $cliente->telefono) }}">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" class="form-control"
                                            value="{{ old('email', $cliente->email) }}">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="contacto">Contacto</label>
                                        <input type="text" name="contacto" class="form-control"
                                            value="{{ old('contacto', $cliente->contacto) }}">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="total_valor_productos">Total Valor Productos</label>
                                        <input type="number" name="total_valor_productos" class="form-control"
                                            value="{{ old('total_valor_productos', $cliente->total_valor_productos) }}">
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="estado">Estado</label>
                                        <select name="estado" class="form-control" required>
                                            <option value="ACTIVO"
                                                {{ old('estado', $cliente->estado) == 'ACTIVO' ? 'selected' : '' }}>Activo
                                            </option>
                                            <option value="INACTIVO"
                                                {{ old('estado', $cliente->estado) == 'INACTIVO' ? 'selected' : '' }}>
                                                Inactivo</option>
                                        </select>
                                    </div>
                                </div>
                            </fieldset>



                            <!-- Sección de Documentos (mantener igual) -->
                            <fieldset class="border p-3 mb-4">
                                <legend class="text-primary"><i class="fa-solid fa-box-open"></i> Productos y Precios
                                    Especiales</legend>

                                <div class="form-group mb-3">
                                    <label for="productos">Productos y Precios Especiales</label>
                                    <div id="productos-container">
                                        @foreach ($productos as $producto)
                                            <div class="card mb-2 producto-item">
                                                <div class="card-body">
                                                    <div class="form-check">
                                                        <input class="form-check-input producto-checkbox" type="checkbox"
                                                            name="productos[]" id="producto-{{ $producto->id }}"
                                                            value="{{ $producto->id }}"
                                                            @if (in_array($producto->id, $cliente->productos->pluck('id')->toArray())) checked @endif
                                                            onchange="mostrarPrecioEspecial('{{ $producto->id }}')">

                                                        <label class="form-check-label" for="producto-{{ $producto->id }}">
                                                            {{ $producto->nombre }} ({{ $producto->codigo }})
                                                            <span class="text-muted">-
                                                                ${{ number_format($producto->valor_producto, 2) }}</span>
                                                        </label>
                                                    </div>

                                                    <div class="precio-especial-container mt-2"
                                                        id="precio-especial-{{ $producto->id }}"
                                                        style="display: {{ in_array($producto->id, $cliente->productos->pluck('id')->toArray()) ? 'block' : 'none' }};">

                                                        <label>Precio Especial</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">$</span>
                                                            <input type="number" step="0.01"
                                                                name="precios_especiales[{{ $producto->id }}]"
                                                                class="form-control"
                                                                value="{{ $cliente->preciosEspeciales->where('producto_id', $producto->id)->first()->precio ?? '' }}"
                                                                placeholder="{{ $producto->valor_producto }}">
                                                            <button class="btn btn-outline-secondary" type="button"
                                                                onclick="resetearPrecio('{{ $producto->id }}')">
                                                                Usar precio base
                                                            </button>
                                                        </div>
                                                        <small class="text-muted">Dejar vacío para usar precio base</small>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </fieldset>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="submit" class="btn btn-primary">Actualizar Cliente</button>
                                <a href="{{ route('clientes.index') }}" class="btn btn-danger">Cancelar</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Función para mostrar/ocultar el campo de precio especial
        function mostrarPrecioEspecial(productoId) {
            const checkbox = document.getElementById(`producto-${productoId}`);
            const precioContainer = document.getElementById(`precio-especial-${productoId}`);

            precioContainer.style.display = checkbox.checked ? 'block' : 'none';
        }

        // Función para resetear el precio especial al precio base
        function resetearPrecio(productoId) {
            const precioInput = document.querySelector(`input[name="precios_especiales[${productoId}]"]`);
            precioInput.value = '';
        }
    </script>
@endsection


