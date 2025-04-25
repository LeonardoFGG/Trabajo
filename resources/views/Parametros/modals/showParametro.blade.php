<!-- Modal de Ver Parametro -->
<div class="modal fade" id="modalShowParametro{{ $parametro->id }}" tabindex="-1"
    aria-labelledby="modalShowParametroLabel{{ $parametro->id }}" aria-hidden="true">
   <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
       <div class="modal-content">
           <div class="modal-header bg-primary text-white">
               <h5 class="modal-title" id="modalShowParametroLabel{{ $parametro->id }}">
                   <i class="fas fa-info-circle"></i> Detalles del Parametro
               </h5>
               <button type="button" class="btn-close text-white" data-bs-dismiss="modal"
                       aria-label="Cerrar"></button>
           </div>
           <div class="modal-body">

            <table class="table table-bordered table-striped table-hover">
                <tbody>
                    <tr>
                        <th><i class="fas fa-hashtag"></i> ID</th>
                        <td>{{ $parametro->id }}</td>
                    </tr>
                    <tr>
                        <th><i class="fas fa-building"></i> Nombre</th>
                        <td>{{ $parametro->nombre }}</td>
                    </tr>
                    <tr>
                        <th><i class="fas fa-align-left"></i> Departamento</th>
                        <td>{{ $parametro->departamento->nombre ?? 'No asignado' }}</td>
                    </tr>
                    
                </tbody>
            </table>

            {{-- <div class="d-flex justify-content-center mt-4">
                <a href="{{ route('parametros.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div> --}}

           </div>
           <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
           </div>
       </div>
   </div>
</div>