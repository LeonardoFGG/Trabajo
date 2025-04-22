<!-- resources/views/ventas/partials/create-modal.blade.php -->
<div class="modal fade" id="createVentaModal" tabindex="-1" aria-labelledby="createVentaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createVentaModalLabel">Nueva Venta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('ventas.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="tipo_venta" class="form-label">Tipo de Venta</label>
                            <select class="form-select @error('tipo_venta') is-invalid @enderror" id="tipo_venta" name="tipo_venta" required>
                                <option value="" selected disabled>Seleccione el tipo</option>
                                <option value="Interna">Interna</option>
                                <option value="Externa">Externa</option>
                            </select>
                            @error('tipo_venta')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="cliente_id" class="form-label">Cliente</label>
                            <select class="form-select @error('cliente_id') is-invalid @enderror" id="cliente_id" name="cliente_id" required>
                                <option value="" selected disabled>Seleccione un cliente</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                                @endforeach
                            </select>
                            @error('cliente_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="empleado_id" class="form-label">Vendedor</label>
                            <select class="form-select @error('empleado_id') is-invalid @enderror" id="empleado_id" name="empleado_id" required>
                                <option value="" selected disabled>Seleccione un vendedor</option>
                                @foreach($empleados as $empleado)
                                    <option value="{{ $empleado->id }}">{{ $empleado->nombre }}</option>
                                @endforeach
                            </select>
                            @error('empleado_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6">
                            <label for="tipo_item_venta" class="form-label">Tipo de Item</label>
                            <select class="form-select @error('tipo_item_venta') is-invalid @enderror" id="tipo_item_venta" name="tipo_item_venta">
                                <option value="" selected disabled>Seleccione el tipo de item</option>
                                <option value="producto">Producto</option>
                                <option value="paquete">Paquete</option>
                            </select>
                            @error('tipo_item_venta')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Sección de Productos (inicialmente oculta) -->
                    <div id="producto_section" class="d-none">
                        <h5 class="border-bottom pb-2 mb-3">Producto</h5>
                        <div class="mb-3">
                            <label for="producto_id" class="form-label">Seleccione un producto</label>
                            <select class="form-select @error('producto_id') is-invalid @enderror" id="producto_id" name="producto_id">
                                <option value="" selected disabled>Seleccione un producto</option>
                                @foreach($productos as $producto)
                                    <option value="{{ $producto->id }}">{{ $producto->nombre }} - {{ $producto->codigo ?: 'Sin código' }}</option>
                                @endforeach
                            </select>
                            @error('producto_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <h6>Productos Adicionales</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Producto</th>
                                                <th>Cantidad</th>
                                                <th>Notas</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($productos as $key => $producto)
                                                <tr>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" id="producto_check_{{ $producto->id }}">
                                                            <label class="form-check-label" for="producto_check_{{ $producto->id }}">
                                                                {{ $producto->nombre }} - {{ $producto->codigo ?: 'Sin código' }}
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control form-control-sm" 
                                                               name="productos[{{ $producto->id }}]" value="0" min="0" 
                                                               id="producto_cantidad_{{ $producto->id }}">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control form-control-sm" 
                                                               name="notas_producto[{{ $producto->id }}]" 
                                                               placeholder="Notas adicionales">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sección de Paquetes (inicialmente oculta) -->
                    <div id="paquete_section" class="d-none">
                        <h5 class="border-bottom pb-2 mb-3">Paquete</h5>
                        <div class="mb-3">
                            <label for="paquete_id" class="form-label">Seleccione un paquete</label>
                            <select class="form-select @error('paquete_id') is-invalid @enderror" id="paquete_id" name="paquete_id">
                                <option value="" selected disabled>Seleccione un paquete</option>
                                @foreach($paquetes as $paquete)
                                    <option value="{{ $paquete->id }}">{{ $paquete->nombre }}</option>
                                @endforeach
                            </select>
                            @error('paquete_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <h6>Paquetes Adicionales</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Paquete</th>
                                                <th>Cantidad</th>
                                                <th>Notas</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($paquetes as $key => $paquete)
                                                <tr>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" id="paquete_check_{{ $paquete->id }}">
                                                            <label class="form-check-label" for="paquete_check_{{ $paquete->id }}">
                                                                {{ $paquete->nombre }}
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control form-control-sm" 
                                                               name="paquetes[{{ $paquete->id }}]" value="0" min="0" 
                                                               id="paquete_cantidad_{{ $paquete->id }}">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control form-control-sm" 
                                                               name="notas_paquete[{{ $paquete->id }}]" 
                                                               placeholder="Notas adicionales">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="detalle_prospeccion" class="form-label">Detalles de la Prospección</label>
                        <textarea class="form-control @error('detalle_prospeccion') is-invalid @enderror" id="detalle_prospeccion" name="detalle_prospeccion" rows="3" placeholder="Describa los detalles iniciales de la prospección"></textarea>
                        @error('detalle_prospeccion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Crear Venta</button>
                </div>
            </form>
        </div>
    </div>
</div>