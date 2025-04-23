<!-- Modal para Editar Cargo -->
<div class="modal fade" id="editModal{{ $cargo->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $cargo->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editModalLabel{{ $cargo->id }}"><i class="fas fa-edit"></i> Editar Cargo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('cargos.update', $cargo->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Campo Nombre -->
                    <div class="form-group mb-3">
                        <label for="nombre">Nombre</label>
                        <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $cargo->nombre_cargo) }}">
                    </div>

                    <!-- Campo Descripción -->
                    <div class="form-group mb-4">
                        <label for="descripcion">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="3" required>{{ old('descripcion', $cargo->descripcion) }}</textarea>
                    </div>

                    <!-- Departamento -->
                    <div class="form-group mb-3">
                        <label for="departamento_id">Departamento</label>
                        <select name="departamento_id" class="form-control" required>
                            <option value="">Seleccione un Departamento</option>
                            @foreach ($departamentos as $departamento)
                                <option value="{{ $departamento->id }}" {{ old('departamento_id', $cargo->departamento_id) == $departamento->id ? 'selected' : '' }}>
                                    {{ $departamento->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Codigo de Afiliación -->
                    <div class="form-group mb-3">
                        <label for="codigo_afiliacion">Codigo de Afiliación</label>
                        <input type="text" name="codigo_afiliacion" class="form-control" value="{{ old('codigo_afiliacion', $cargo->codigo_afiliacion) }}" required>
                    </div>

                    <!-- Salario Básico -->
                    <div class="form-group mb-3">
                        <label for="salario_basico">Salario Básico</label>
                        <input type="text" name="salario_basico" class="form-control" value="{{ old('salario_basico', $cargo->salario_basico) }}" required>
                    </div>

                    <!-- Botones Enviar -->
                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-success btn-md">
                            <i class="fas fa-save"></i> Guardar Cambios
                        </button>
                        <button type="button" class="btn btn-secondary btn-md ms-2" data-bs-dismiss="modal">
                            <i class="fas fa-times"></i> Cerrar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
