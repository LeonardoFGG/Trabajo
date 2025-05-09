<div class="modal fade" id="createCargoModal" tabindex="-1" aria-labelledby="createCargoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createCargoModalLabel"><i class="fa-solid fa-building"></i> Crear Nuevo Cargo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('cargos.store') }}" method="POST">
                    @csrf

                    <!-- Campo Nombre -->
                    <div class="form-group mb-3">
                        <label for="nombre_cargo">Nombre</label>
                        <input type="text" name="nombre_cargo" class="form-control" value="{{ old('nombre_cargo') }}" required>
                    </div>

                    <!-- Campo Descripción -->
                    <div class="form-group mb-3">
                        <label for="descripcion">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="3" required>{{ old('descripcion') }}</textarea>
                    </div>

                    <!-- Codigo de Afiliacion -->
                    <div class="form-group mb-3">
                        <label for="codigo_afiliacion">Codigo de Afiliacion</label>
                        <input type="text" name="codigo_afiliacion" class="form-control" value="{{ old('codigo_afiliacion') }}" required>
                    </div>

                    <!-- Salario Basico -->
                    <div class="form-group mb-3">
                        <label for="salario_basico">Salario Basico</label>
                        <input type="number" name="salario_basico" class="form-control" value="{{ old('salario_basico') }}" required>
                    </div>

                    <!-- Campo Selección de Departamento -->
                    <div class="form-group mb-3">
                        <label for="departamento_id">Departamento</label>
                        <select name="departamento_id" class="form-control" required>
                            <option value="">Seleccione un departamento</option>
                            @foreach ($departamentos as $departamento)
                                <option value="{{ $departamento->id }}" {{ old('departamento_id') == $departamento->id ? 'selected' : '' }}>
                                    {{ $departamento->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Botones Enviar -->
                    <div class="form-group text-center mt-4">
                        <button type="submit" class="btn btn-success me-2">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fas fa-arrow-left"></i> Volver
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
