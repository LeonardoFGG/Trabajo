<!-- resources/views/ventas/partials/workflow-modal.blade.php -->
<div class="modal fade" id="workflowModal" tabindex="-1" aria-labelledby="workflowModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="workflowModalLabel">Workflow de Venta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <div class="alert alert-info">
                    <strong>Estado comercial actual:</strong> <span id="estado_actual">-</span>
                </div>

                <form action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="nuevo_estado" id="nuevo_estado" value="">
                    
                    <!-- Formulario para estado: No hay siguiente estado -->
                    <div id="no_next_state" class="estado-form d-none">
                        <div class="alert alert-warning">
                            Esta venta ya está en el estado final (Cierre). No hay más estados disponibles.
                        </div>
                    </div>
                    
                    <!-- Formulario para estado: Contacto -->
                    <div id="contacto_form" class="estado-form d-none">
                        <h4 class="mb-3">Avanzar a etapa de Contacto</h4>
                        
                        <div class="mb-3">
                            <label for="fecha_contacto" class="form-label">Fecha de contacto</label>
                            <input type="date" class="form-control" id="fecha_contacto" name="fecha_contacto" value="{{ date('Y-m-d') }}">
                        </div>
                        
                        <div class="mb-3">
                            <label for="canal_comunicacion" class="form-label">Canal de comunicación</label>
                            <select class="form-select" id="canal_comunicacion" name="canal_comunicacion" required>
                                <option value="" selected disabled>Seleccione un canal</option>
                                <option value="Email">Email</option>
                                <option value="Teléfono">Teléfono</option>
                                <option value="Reunión presencial">Reunión presencial</option>
                                <option value="Videollamada">Videollamada</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="detalle_contacto" class="form-label">Detalles del contacto</label>
                            <textarea class="form-control" id="detalle_contacto" name="detalle_contacto" rows="3" placeholder="Describa los detalles del contacto con el cliente"></textarea>
                        </div>
                    </div>
                    
                    <!-- Formulario para estado: Presentación -->
                    <div id="presentación_form" class="estado-form d-none">
                        <h4 class="mb-3">Avanzar a etapa de Presentación</h4>
                        
                        <div class="mb-3">
                            <label for="fecha_presentacion" class="form-label">Fecha de presentación</label>
                            <input type="date" class="form-control" id="fecha_presentacion" name="fecha_presentacion" value="{{ date('Y-m-d') }}">
                        </div>
                        
                        <div class="mb-3">
                            <label for="observacion_presentacion" class="form-label">Observaciones</label>
                            <textarea class="form-control" id="observacion_presentacion" name="observacion_presentacion" rows="3" placeholder="Describa cómo transcurrió la presentación y las observaciones relevantes"></textarea>
                        </div>
                    </div>
                    
                    <!-- Formulario para estado: Propuesta -->
                    <div id="propuesta_form" class="estado-form d-none">
                        <h4 class="mb-3">Avanzar a etapa de Propuesta</h4>
                        
                        <div class="mb-3">
                            <label for="fecha_propuesta" class="form-label">Fecha de propuesta</label>
                            <input type="date" class="form-control" id="fecha_propuesta" name="fecha_propuesta" value="{{ date('Y-m-d') }}">
                        </div>
                        
                        <div class="mb-3">
                            <label for="archivo_propuesta" class="form-label">Archivo de propuesta</label>
                            <input type="file" class="form-control" id="archivo_propuesta" name="archivo_propuesta">
                            <div class="form-text">Suba el archivo de la propuesta (PDF, Word, Excel)</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="detalle_propuesta" class="form-label">Detalles de la propuesta</label>
                            <textarea class="form-control" id="detalle_propuesta" name="detalle_propuesta" rows="3" placeholder="Describa los detalles más relevantes de la propuesta"></textarea>
                        </div>
                    </div>
                    
                    <!-- Formulario para estado: Negociación -->
                    <div id="negociación_form" class="estado-form d-none">
                        <h4 class="mb-3">Avanzar a etapa de Negociación</h4>
                        
                        <div class="mb-3">
                            <label for="fecha_negociacion" class="form-label">Fecha de negociación</label>
                            <input type="date" class="form-control" id="fecha_negociacion" name="fecha_negociacion" value="{{ date('Y-m-d') }}">
                        </div>
                        
                        <div class="mb-3">
                            <label for="archivo_negociacion" class="form-label">Archivo de negociación</label>
                            <input type="file" class="form-control" id="archivo_negociacion" name="archivo_negociacion">
                            <div class="form-text">Suba el archivo con los términos negociados (PDF, Word, Excel)</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="detalle_negociacion" class="form-label">Detalles de la negociación</label>
                            <textarea class="form-control" id="detalle_negociacion" name="detalle_negociacion" rows="3" placeholder="Describa los términos negociados y puntos importantes"></textarea>
                        </div>
                    </div>
                    
                    <!-- Formulario para estado: Cierre -->
                    <div id="cierre_form" class="estado-form d-none">
                        <h4 class="mb-3">Avanzar a etapa de Cierre</h4>
                        
                        <div class="mb-3">
                            <label for="fecha_venta" class="form-label">Fecha de venta</label>
                            <input type="date" class="form-control" id="fecha_venta" name="fecha_venta" value="{{ date('Y-m-d') }}">
                        </div>
                        
                        <div class="mb-3">
                            <label for="fecha_contrato" class="form-label">Fecha de contrato</label>
                            <input type="date" class="form-control" id="fecha_contrato" name="fecha_contrato">
                        </div>
                        
                        <div class="mb-3">
                            <label for="fecha_cobro" class="form-label">Fecha de cobro</label>
                            <input type="date" class="form-control" id="fecha_cobro" name="fecha_cobro">
                        </div>
                        
                        <div class="mb-3">
                            <label for="fecha_expiracion" class="form-label">Fecha de expiración</label>
                            <input type="date" class="form-control" id="fecha_expiracion" name="fecha_expiracion">
                        </div>
                        
                        <div class="mb-3">
                            <label for="anexo_contrato" class="form-label">Anexo de contrato</label>
                            <input type="file" class="form-control" id="anexo_contrato" name="anexo_contrato">
                            <div class="form-text">Suba el contrato firmado (PDF)</div>
                        </div>
                        
                        
                    </div>
                    
                    <div class="text-end mt-4">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Avanzar Estado</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>