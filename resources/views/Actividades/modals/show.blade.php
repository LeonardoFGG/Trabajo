<div class="modal fade" id="modalShow{{ $actividad->id }}" tabindex="-1" aria-labelledby="modalShowLabel{{ $actividad->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalShowLabel{{ $actividad->id }}">
                    <i class="fas fa-info-circle"></i> Detalles de la Actividad
                </h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                {{-- Aquí estás incluyendo tu tarjeta de detalle --}}
                @include('Actividades.partials.show-content', ['actividades' => $actividad])
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
