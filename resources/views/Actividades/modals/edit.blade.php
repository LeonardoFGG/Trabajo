<div class="modal fade" id="modalEdit{{ $actividad->id }}" tabindex="-1" aria-labelledby="modalEditLabel{{ $actividad->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <form action="{{ route('actividades.update', $actividad->id) }}" method="POST" class="modal-content">
            @csrf
            @method('PUT')

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalEditLabel{{ $actividad->id }}">
                    <i class="fas fa-edit"></i> Editar Actividad
                </h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">
                {{-- Errores --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Información del Cliente y Empleado --}}
                <fieldset class="border p-3 mb-4">
                    <legend class="text-primary"><i class="fas fa-users"></i> Información del Cliente y Empleado</legend>
                    <div class="row">
                        <!-- Cliente -->
                        <div class="form-group col-md-6">
                            <label for="cliente_id">Clientes</label>
                            <select name="cliente_id" id="cliente_id" class="form-select">
                                <option value="">Seleccione un Cliente & Cooperativa</option>
                                @foreach ($clientes as $cliente)
                                    <option value="{{ $cliente->id }}" {{ $cliente->id == $actividad->cliente_id ? 'selected' : '' }}>
                                        {{ $cliente->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Empleado -->
                        <div class="form-group col-md-6">
                            <label for="empleado_id">Empleado</label>
                            <select name="empleado_id" id="empleado_id" class="form-select" required>
                                @foreach ($empleados as $empleado)
                                    <option value="{{ $empleado->id }}" {{ $empleado->id == $actividad->empleado_id ? 'selected' : '' }}>
                                        {{ $empleado->nombre1 }} {{ $empleado->apellido1 }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Departamento -->
                        <div class="form-group col-md-6">
                            <label for="departamento_id">Departamento</label>
                            <select name="departamento_id" id="departamento_id" class="form-select" required>
                                @foreach ($departamentos as $departamento)
                                    <option value="{{ $departamento->id }}" {{ $departamento->id == $actividad->departamento_id ? 'selected' : '' }}>
                                        {{ $departamento->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Cargo -->
                        <div class="form-group col-md-6">
                            <label for="cargo_id">Cargo</label>
                            <select name="cargo_id" id="cargo_id" class="form-select" required>
                                @foreach ($cargos as $cargo)
                                    <option value="{{ $cargo->id }}" {{ $cargo->id == $actividad->cargo_id ? 'selected' : '' }}>
                                        {{ $cargo->nombre_cargo }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </fieldset>

                {{-- Detalles de la Actividad --}}
                <fieldset class="border p-3 mb-4">
                    <legend class="text-primary"><i class="fas fa-tasks"></i> Detalles de la Actividad</legend>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="descripcion">Descripción</label>
                            <textarea name="descripcion" class="form-control">{{ old('descripcion', $actividad->descripcion) }}</textarea>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="codigo_osticket">Código Osticket</label>
                            <input type="text" name="codigo_osticket" class="form-control" value="{{ old('codigo_osticket', $actividad->codigo_osticket) }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="semanal_diaria">Frecuencia</label>
                            <select name="semanal_diaria" class="form-select" required>
                                <option value="SEMANAL" {{ old('semanal_diaria', $actividad->semanal_diaria) == 'SEMANAL' ? 'selected' : '' }}>Semanal</option>
                                <option value="DIARIO" {{ old('semanal_diaria', $actividad->semanal_diaria) == 'DIARIO' ? 'selected' : '' }}>Diario</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="estado">Estado</label>
                            <select name="estado" class="form-select" required>
                                <option value="EN CURSO" {{ old('estado', $actividad->estado) == 'EN CURSO' ? 'selected' : '' }}>En Curso</option>
                                <option value="FINALIZADO" {{ old('estado', $actividad->estado) == 'FINALIZADO' ? 'selected' : '' }}>Finalizado</option>
                                <option value="PENDIENTE" {{ old('estado', $actividad->estado) == 'PENDIENTE' ? 'selected' : '' }}>Pendiente</option>
                            </select>
                        </div>
                    </div>
                </fieldset>

                {{-- Fechas y Tiempo --}}
                <fieldset class="border p-3 mb-4">
                    <legend class="text-primary"><i class="fas fa-calendar-alt"></i> Fechas y Tiempo Estimado</legend>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="fecha_inicio">Fecha de Inicio</label>
                            <input type="date" name="fecha_inicio" class="form-control" value="{{ old('fecha_inicio', $actividad->fecha_inicio->format('Y-m-d')) }}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="fecha_fin">Fecha de Fin</label>
                            <input type="date" name="fecha_fin" class="form-control" value="{{ old('fecha_fin', $actividad->fecha_fin ? $actividad->fecha_fin->format('Y-m-d') : '') }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="tiempo_estimado">Tiempo Estimado (minutos)</label>
                            <input type="number" name="tiempo_estimado" class="form-control" value="{{ old('tiempo_estimado', $actividad->tiempo_estimado) }}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="avance">Avance (%)</label>
                            <input type="number" name="avance" class="form-control" value="{{ old('avance', $actividad->avance) }}" min="0" max="100" required>
                        </div>
                    </div>
                </fieldset>

                {{-- Información Adicional --}}
                <fieldset class="border p-3 mb-3">
                    <legend class="text-primary"><i class="fas fa-info-circle"></i> Información Adicional</legend>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="observaciones">Observaciones</label>
                            <textarea name="observaciones" class="form-control">{{ old('observaciones', $actividad->observaciones) }}</textarea>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="prioridad">Prioridad</label>
                            <select name="prioridad" class="form-select" required>
                                <option value="ALTA" {{ old('prioridad', $actividad->prioridad) == 'ALTA' ? 'selected' : '' }}>Alta</option>
                                <option value="MEDIA" {{ old('prioridad', $actividad->prioridad) == 'MEDIA' ? 'selected' : '' }}>Media</option>
                                <option value="BAJA" {{ old('prioridad', $actividad->prioridad) == 'BAJA' ? 'selected' : '' }}>Baja</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="repetitivo">Repetitivo</label>
                            <select name="repetitivo" class="form-select" required>
                                <option value="1" {{ old('repetitivo', $actividad->repetitivo) == '1' ? 'selected' : '' }}>Sí</option>
                                <option value="0" {{ old('repetitivo', $actividad->repetitivo) == '0' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="error">Tipo de Error</label>
                            <select name="error" class="form-select" required>
                                <option value="CLIENTE" {{ old('error', $actividad->error) == 'CLIENTE' ? 'selected' : '' }}>Cliente</option>
                                <option value="SOFTWARE" {{ old('error', $actividad->error) == 'SOFTWARE' ? 'selected' : '' }}>Software</option>
                                <option value="MEJORA ERROR" {{ old('error', $actividad->error) == 'MEJORA ERROR' ? 'selected' : '' }}>Mejora Error</option>
                                <option value="DESARROLLO" {{ old('error', $actividad->error) == 'DESARROLLO' ? 'selected' : '' }}>Desarrollo</option>
                                <option value="OTRO" {{ old('error', $actividad->error) == 'OTRO' ? 'selected' : '' }}>Otros</option>
                            </select>
                        </div>
                    </div>
                </fieldset>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Actualizar
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Cancelar
                </button>
            </div>
        </form>
    </div>
</div>
