@extends('layouts.app')

@section('content')
    <div class="container mt-4" style="max-width: 700px;">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Crear Parámetro</h4>

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

                <form action="{{ route('parametros.store') }}" method="POST">
                    @csrf

                    <div class="form-group mt-3">
                        <label for="nombre">Nombre del Parámetro <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}"
                            required>

                    </div>



                    <div class="form-group mt-3">
                        <label for="departamento_id">Departamento <span class="text-danger">*</span></label>
                        <select name="departamento_id" class="form-control" required>
                            <option value="">Seleccione un Departamento</option>
                            @foreach ($departamentos as $departamento)
                                <option value="{{ $departamento->id }}">{{ $departamento->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group  mt-4 mb-0">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <a href="{{ route('parametros.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection

                    

