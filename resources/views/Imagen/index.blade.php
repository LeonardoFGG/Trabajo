@extends('layouts.app')

@section('content')
    <div class="container mt-7">
        <h1 class="text-center mb-8">Subir la imagen Para anuncios</h1>

        @if (session('success'))
            <div class="alert alert-success" id="success-message">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger" id="error-message">
                {{ session('error') }}
            </div>
        @endif

        <!-- Verifica si el usuario tiene permisos antes de mostrar el formulario -->
        @if (Auth::user()->id == 8 || Auth::user()->role === 'admin')
            <form action="{{ route('images.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="image">Selecciona una imagen:</label>
                    <input type="file" name="image" id="image" required class="form-control">
                </div>

                <button type="submit" class="btn btn-primary">Subir Imagen</button>
            </form>
        @else
            <div class="alert alert-warning mt-3">No tienes permiso para subir imágenes.</div>
        @endif

        
        <h1 class="text-center mb-8">Listado de Imagenes</h1>
        <div class="table-responsive">
            <table id="imagenes-table" class="table table-hover table-bordered w-100 table-sm">
                <thead class="thead-dark text-center">
                    <tr>
                        <th scope="col">Imagen</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($imagenes as $imagen)
                        <tr>
                            <td class="text-center">
                                <img src="{{ asset('uploads/' . $imagen->file_path) }}" alt="Imagen" class="img-thumbnail" style="width: 100px; height: 100px;">
                            </td>
                            <td class="text-center">
                                
                                <form action="{{ route('imagen.destroy', $imagen->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar esta imagen?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
