@extends('layouts.app')

@section('content')
    <div class="container mt-4" style="max-width: 900px;">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white text-center">
                        <h2><i class="fas fa-user-plus"></i> Registrar Nuevo Cliente</h2>
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

                        <form action="{{ route('clientes.store') }}" method="POST" enctype="multipart/form-data" class="p-4">
                            @csrf

                            <!-- Campo Producto -->
                            <div class="form-group mb-3">
                                <label for="productos">Selecciona Productos</label>
                                <div id="productos">
                                    @foreach ($productos as $producto)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="productos[]" id="producto{{ $producto->id }}" value="{{ $producto->id }}">
                                            <label class="form-check-label" for="producto{{ $producto->id }}">
                                                {{ $producto->nombre }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Campos alineados con etiqueta a la derecha -->
                            @php
                                $fields = [
                                    'nombre' => 'Nombre',
                                    'direccion' => 'Dirección',
                                    'telefono' => 'Teléfono',
                                    'email' => 'Email',
                                    'contacto' => 'Contacto'
                                ];
                            @endphp

                            @foreach ($fields as $name => $label)
                                <div class="form-group row mb-3">
                                    <label for="{{ $name }}" class="col-md-4 col-form-label text-md-right">
                                        <strong>{{ $label }}</strong>
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" name="{{ $name }}" class="form-control mb-3" value="{{ old($name) }}">
                                    </div>
                                </div>
                            @endforeach

                            <!-- Campo Contrato de Implementación -->
                            <div class="form-group row mb-3">
                                <label for="contrato_implementacion" class="col-md-4 col-form-label text-md-right">
                                    <strong>Contrato de Implementación</strong>
                                </label>
                                <div class="col-md-6">
                                    <input type="file" name="contrato_implementacion" class="form-control">
                                </div>
                            </div>

                            <!-- Campo Convenio de Datos -->
                            <div class="form-group row mb-3">
                                <label for="convenio_datos" class="col-md-4 col-form-label text-md-right">
                                    <strong>Convenio de Datos</strong>
                                </label>
                                <div class="col-md-6">
                                    <input type="file" name="convenio_datos" class="form-control">
                                </div>
                            </div>

                            <!-- Campo Documentos Otros -->
                            <div class="form-group row mb-3">
                                <label for="documento_otros" class="col-md-4 col-form-label text-md-right">
                                    <strong>Documentos Otros</strong>
                                </label>
                                <div class="col-md-6">
                                    <input type="file" name="documento_otros[]" class="form-control" multiple>
                                </div>
                            </div>

                            <!-- Total de los Productos -->
                            <div class="form-group row mb-3">
                                <label for="total_valor_productos" class="col-md-4 col-form-label text-md-right">
                                    <strong>Valor Total de Productos</strong>
                                </label>
                                <div class="col-md-6">
                                    <input type="number" name="total_valor_productos" id="total_valor_productos" class="form-control">
                                </div>
                            </div>

                            <!-- Campo Estado -->
                            <div class="form-group row mb-3">
                                <label for="estado" class="col-md-4 col-form-label text-md-right">
                                    <strong>Estado</strong>
                                </label>
                                <div class="col-md-6">
                                    <select name="estado" class="form-control" required>
                                        <option value="ACTIVO" {{ old('estado') == 'ACTIVO' ? 'selected' : '' }}>Activo</option>
                                        <option value="INACTIVO" {{ old('estado') == 'INACTIVO' ? 'selected' : '' }}>Inactivo</option>
                                    </select>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-primary">Guardar Cliente</button>
                                <a href="{{ route('clientes.index') }}" class="btn btn-danger">Cancelar</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#productos').select2({
                placeholder: "Selecciona productos",
                allowClear: true
            });
        });
    </script>

@endsection
