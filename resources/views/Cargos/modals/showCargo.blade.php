<!-- Modal de Ver Cargo -->
<div class="modal fade" id="modalShowCargos{{ $cargo->id }}" tabindex="-1"
    aria-labelledby="modalShowCargosLabel{{ $cargo->id }}" aria-hidden="true">
   <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
       <div class="modal-content">
           <div class="modal-header bg-primary text-white">
               <h5 class="modal-title" id="modalShowCargosLabel{{ $cargo->id }}">
                   <i class="fas fa-info-circle"></i> Detalles del Cargo
               </h5>
               <button type="button" class="btn-close text-white" data-bs-dismiss="modal"
                       aria-label="Cerrar"></button>
           </div>
           <div class="modal-body">
               <table class="table table-bordered table-striped table-hover">
                   <tbody>
                       <tr>
                           <th><i class="fas fa-hashtag"></i> ID</th>
                           <td>{{ $cargo->id }}</td>
                       </tr>
                       <tr>
                           <th><i class="fas fa-building"></i> Nombre</th>
                           <td>{{ $cargo->nombre_cargo }}</td>
                       </tr>
                       <tr>
                           <th><i class="fas fa-align-left"></i> Descripción</th>
                           <td>{{ $cargo->descripcion }}</td>
                       </tr>
                       <tr>
                           <th><i class="fas fa-hashtag"></i> Codigo de Afiliacion</th>
                           <td>{{ $cargo->codigo_afiliacion }}</td>
                       </tr>
                       <tr>
                           <th><i class="fas fa-hashtag"></i> Salario Basico</th>
                           <td>{{ $cargo->salario_basico }}</td>
                       </tr>
                       <tr>
                           <th><i class="fas fa-building"></i> Departamento</th>
                           <td>{{ $cargo->departamento->nombre }}</td>
                       </tr>
                   </tbody>
               </table>
           </div>
           <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
           </div>
       </div>
   </div>
</div>
