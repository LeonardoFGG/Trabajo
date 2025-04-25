<div class="modal fade" id="createParametroModal" tabindex="-1" aria-labelledby="createParametroModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createParametroModalLabel"><i class="fa-solid fa-building"></i> Crear Parametro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <form action="{{ route('parametros.store') }}" method="POST">
                    @csrf

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

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
</div>