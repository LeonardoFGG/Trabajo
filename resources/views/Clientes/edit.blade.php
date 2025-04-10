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

                                    <div class="form-group mb-3">
                                        <label for="productos">Selecciona Productos</label>
                                        <div id="productos">
                                            @foreach ($productos as $producto)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="productos[]" id="producto{{ $producto->id }}" value="{{ $producto->id }}"
                                                        @if(in_array($producto->id, $cliente->productos->pluck('id')->toArray())) checked @endif>
                                                    <label class="form-check-label" for="producto{{ $producto->id }}">
                                                        {{ $producto->nombre }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Campo Nombre -->
                                    <div class="form-group col-md-6">
                                        <label for="nombre">Nombre</label>
                                        <input type="text" name="nombre" class="form-control"
                                            value="{{ old('nombre', $cliente->nombre) }}" required>
                                    </div>

                                    <!-- Campo Dirección -->
                                    <div class="form-group col-md-6">
                                        <label for="direccion">Dirección</label>
                                        <input type="text" name="direccion" class="form-control"
                                            value="{{ old('direccion', $cliente->direccion) }}" >
                                    </div>

                                    <!-- Campo Teléfono -->
                                    <div class="form-group col-md-6">
                                        <label for="telefono">Teléfono</label>
                                        <input type="text" name="telefono" class="form-control"
                                            value="{{ old('telefono', $cliente->telefono) }}" >
                                    </div>

                                    <!-- Campo Email -->
                                    <div class="form-group col-md-6">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" class="form-control"
                                            value="{{ old('email', $cliente->email) }}" >
                                    </div>

                                    <!-- Campo Contacto -->
                                    <div class="form-group col-md-6">
                                        <label for="contacto">Contacto</label>
                                        <input type="text" name="contacto" class="form-control"
                                            value="{{ old('contacto', $cliente->contacto) }}">
                                    </div>

                                    <!-- Campo Total Valor Productos -->
                                    <div class="form-group col-md-6">
                                        <label for="total_valor_productos">Total Valor Productos</label>
                                        <input type="number" name="total_valor_productos" class="form-control"
                                            value="{{ old('total_valor_productos', $cliente->total_valor_productos) }}" >
                                    </div>


                                    <!-- Campo Estado -->
                                    <div class="form-group col-md-6">
                                        <label for="estado">Estado</label>
                                        <select name="estado" class="form-control" required>
                                            <option value="ACTIVO"
                                                {{ old('estado', $cliente->estado) == 'ACTIVO' ? 'selected' : '' }}>Activo
                                            </option>
                                            <option value="INACTIVO"
                                                {{ old('estado', $cliente->estado) == 'INACTIVO' ? 'selected' : '' }}>
                                                Inactivo
                                            </option>
                                        </select>
                                    </div>
                                </div>

                            </fieldset>

                            <fieldset class="border p-3 mb-4">
                                <legend class="text-primary"><i class="fa-solid fa-file"></i> Documentos</legend>
                                <div class="row">

                                    <!-- Campo Contrato Implementación -->
                                    <div class="form-group col-md-6">
                                        <label for="contrato_implementacion">Contrato de Implementación</label>
                                        <input type="file" name="contrato_implementacion" class="form-control">
                                        @if ($cliente->contrato_implementacion)
                                            <p class="mt-1">Archivo actual: <a
                                                    href="{{ asset('storage/' . $cliente->contrato_implementacion) }}"
                                                    target="_blank">Ver Contrato de Implementación</a></p>
                                        @endif
                                    </div>

                                    <!-- Campo Convenio Datos -->
                                    <div class="form-group col-md-6">
                                        <label for="convenio_datos">Convenio de Datos</label>
                                        <input type="file" name="convenio_datos" class="form-control">
                                        @if ($cliente->convenio_datos)
                                            <p class="mt-1">Archivo actual: <a
                                                    href="{{ asset('storage/' . $cliente->convenio_datos) }}"
                                                    target="_blank">Ver Convenio de Datos</a></p>
                                        @endif
                                    </div>

                                    <!-- Campo Documentos Otros -->
                                    <div class="form-group col-md-6">
                                        <label for="documento_otros">Documentos Otros</label>
                                        <input type="file" name="documento_otros" class="form-control">
                                        @if ($cliente->documento_otros)
                                            <p class="mt-1">Archivo actual: <a
                                                    href="{{ asset('storage/' . $cliente->documento_otros) }}"
                                                    target="_blank">Ver Documentos Otros</a></p>
                                        @endif
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

@endsection
