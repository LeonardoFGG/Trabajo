<!-- Modal para Editar Parametro -->
<div class="modal fade" id="editParametro{{ $parametro->id }}" tabindex="-1"
    aria-labelledby="editParametroLabel{{ $parametro->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editParametroLabel{{ $parametro->id }}"><i class="fas fa-edit"></i> Editar
                    Parametros</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('parametros.update', $parametro->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group
                                mt-3">
                        <label for="nombre_parametro">Nombre del Parámetro <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" class="form-control"
                            value="{{ old('nombre', $parametro->nombre) }}" required>

                    </div>

                    <div class="form-group mt-3">
                        <label for="departamento_id">Departamento <span class="text-danger">*</span></label>
                        <select name="departamento_id" class="form-control" required>
                            <option value="">Seleccione un Departamento</option>
                            @foreach ($departamentos as $departamento)
                                <option value="{{ $departamento->id }}"
                                    {{ $departamento->id == $parametro->departamento_id ? 'selected' : '' }}>
                                    {{ $departamento->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group
                                mt-4 mb-0">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <a href="{{ route('parametros.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
