<div class="modal fade" id="createVentaModal" tabindex="-1" aria-labelledby="createVentaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="createVentaModalLabel">Nueva Venta - Workflow</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="ventaForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <!-- Indicador de pasos -->
                    <ul class="nav nav-pills mb-4" id="ventaSteps">
                        <li class="nav-item">
                            <a class="nav-link active" data-step="1">Tipo de Venta</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-step="2">Prospección</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-step="3">Contacto</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-step="4">Presentación</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-step="5">Propuesta</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-step="6">Negociación</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-step="7">Cierre</a>
                        </li>
                    </ul>

                    <!-- Paso 1: Tipo de Venta -->
                    <div class="step" data-step="1">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tipo de Venta *</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipo_venta" id="tipo_interna"
                                    value="Interna" checked>
                                <label class="form-check-label" for="tipo_interna">
                                    Venta Interna (Cliente existente)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipo_venta" id="tipo_externa"
                                    value="Externa">
                                <label class="form-check-label" for="tipo_externa">
                                    Venta Externa (Nuevo cliente)
                                </label>
                            </div>
                        </div>

                        <!-- Contenedor para cliente existente -->
                        <div class="mb-3" id="clienteExistenteContainer">
                            <label for="cliente_id" class="form-label">Seleccione un Cliente *</label>
                            <select class="form-select" id="cliente_id" name="cliente_id" required>
                                <option value="">-- Seleccione un cliente --</option>
                                @foreach ($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">{{ $cliente->nombre }} -
                                        {{ $cliente->telefono }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Contenedor para nuevo cliente -->
                        <div class="mb-3 d-none" id="nuevoClienteContainer">
                            <h5 class="mb-3">Datos del Nuevo Cliente</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nombre" class="form-label">Nombre *</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="telefono" class="form-label">Teléfono *</label>
                                    <input type="text" class="form-control" id="telefono" name="telefono">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="direccion" class="form-label">Dirección</label>
                                    <input type="text" class="form-control" id="direccion" name="direccion">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="contacto" class="form-label">Persona de Contacto</label>
                                <input type="text" class="form-control" id="contacto" name="contacto">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="empleado_id" class="form-label">Responsable de Venta *</label>
                            <select class="form-select" id="empleado_id" name="empleado_id" required>
                                <option value="">-- Seleccione un empleado --</option>
                                @foreach ($empleados as $empleado)
                                    <option value="{{ $empleado->id }}">{{ $empleado->nombre1 }}
                                        {{ $empleado->apellido1 }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Paso 2: Prospección -->
                    <div class="step d-none" data-step="2">
                        <h5>Prospección</h5>
                        <div class="mb-3">
                            <label for="detalle_prospeccion" class="form-label">Detalles de la Prospección</label>
                            <textarea class="form-control" id="detalle_prospeccion" name="detalle_prospeccion" rows="4"></textarea>
                        </div>
                    </div>

                    <!-- Paso 3: Contacto -->
                    <div class="step d-none" data-step="3">
                        <h5>Contacto</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="fecha_contacto" class="form-label">Fecha de Contacto *</label>
                                <input type="date" class="form-control" id="fecha_contacto"
                                    name="fecha_contacto">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="canal_comunicacion" class="form-label">Canal de Comunicación *</label>
                                <select class="form-select" id="canal_comunicacion" name="canal_comunicacion">
                                    <option value="Llamada">Llamada Telefónica</option>
                                    <option value="Email">Correo Electrónico</option>
                                    <option value="Visita">Visita Presencial</option>
                                    <option value="Redes">Redes Sociales</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="detalle_contacto" class="form-label">Detalles del Contacto *</label>
                            <textarea class="form-control" id="detalle_contacto" name="detalle_contacto" rows="4"></textarea>
                        </div>
                    </div>

                    <!-- Paso 4: Presentación -->
                    <div class="step d-none" data-step="4">
                        <h5>Presentación</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="fecha_presentacion" class="form-label">Fecha de Presentación *</label>
                                <input type="date" class="form-control" id="fecha_presentacion"
                                    name="fecha_presentacion">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tipo_item_venta" class="form-label">Tipo de Item *</label>
                                <select class="form-select" id="tipo_item_venta" name="tipo_item_venta">
                                    <option value="producto">Producto</option>
                                    <option value="paquete">Paquete</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3" id="productoContainer">
                            <label for="producto_id" class="form-label">Producto</label>
                            <select class="form-select" id="producto_id" name="producto_id">
                                <option value="">-- Seleccione un producto --</option>
                                @foreach ($productos as $producto)
                                    <option value="{{ $producto->id }}" data-precio="{{ $producto->valor_producto }}">
                                        {{ $producto->nombre }} - ${{ number_format($producto->valor_producto, 2) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 d-none" id="paqueteContainer">
                            <label for="paquete_id" class="form-label">Paquete</label>
                            <select class="form-select" id="paquete_id" name="paquete_id">
                                <option value="">-- Seleccione un paquete --</option>
                                @foreach ($paquetes as $paquete)
                                    <option value="{{ $paquete->id }}" data-precio="{{ $paquete->precio }}">
                                        {{ $paquete->nombre }} - ${{ number_format($paquete->precio, 2) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="observacion_presentacion" class="form-label">Observaciones *</label>
                            <textarea class="form-control" id="observacion_presentacion" name="observacion_presentacion" rows="4"></textarea>
                        </div>

                        <!-- Sección para múltiples productos -->
                        <div class="mb-3">
                            <label class="form-label">Productos Adicionales</label>
                            @foreach ($productos as $producto)
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label>{{ $producto->nombre }}
                                            (${{ number_format($producto->valor_producto, 2) }})
                                        </label>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" name="productos[{{ $producto->id }}]"
                                            class="form-control" min="0" value="0">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="notas_producto[{{ $producto->id }}]"
                                            class="form-control" placeholder="Notas">
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Sección para múltiples paquetes -->
                        <div class="mb-3">
                            <label class="form-label">Paquetes Adicionales</label>
                            @foreach ($paquetes as $paquete)
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label>{{ $paquete->nombre }}
                                            (${{ number_format($paquete->precio, 2) }})
                                        </label>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" name="paquetes[{{ $paquete->id }}]"
                                            class="form-control" min="0" value="0">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="notas_paquete[{{ $paquete->id }}]"
                                            class="form-control" placeholder="Notas">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Paso 5: Propuesta -->
                    <div class="step d-none" data-step="5">
                        <h5>Propuesta</h5>
                        <div class="mb-3">
                            <label for="archivo_propuesta" class="form-label">Documento de Propuesta</label>
                            <input type="file" class="form-control" id="archivo_propuesta"
                                name="archivo_propuesta">
                        </div>
                        <div class="mb-3">
                            <label for="detalle_propuesta" class="form-label">Detalles de la Propuesta *</label>
                            <textarea class="form-control" id="detalle_propuesta" name="detalle_propuesta" rows="4"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="fecha_propuesta" class="form-label">Fecha de Envío *</label>
                            <input type="date" class="form-control" id="fecha_propuesta" name="fecha_propuesta">
                        </div>
                    </div>

                    <!-- Paso 6: Negociación -->
                    <div class="step d-none" data-step="6">
                        <h5>Negociación</h5>
                        <div class="mb-3">
                            <label for="archivo_negociacion" class="form-label">Documento de Negociación</label>
                            <input type="file" class="form-control" id="archivo_negociacion"
                                name="archivo_negociacion">
                        </div>
                        <div class="mb-3">
                            <label for="detalle_negociacion" class="form-label">Detalles de la Negociación *</label>
                            <textarea class="form-control" id="detalle_negociacion" name="detalle_negociacion" rows="4"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="descuento" class="form-label">Descuento Aplicado (%)</label>
                                <input type="number" class="form-control" id="descuento" name="descuento"
                                    min="0" max="100" step="0.01">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="porcentaje_descuento_aprobado_por" class="form-label">Aprobado Por</label>
                                <select class="form-select" id="porcentaje_descuento_aprobado_por"
                                    name="porcentaje_descuento_aprobado_por">
                                    <option value="Freelance">Freelance (5%)</option>
                                    <option value="Gerencia Comercial">Gerencia Comercial (10%)</option>
                                    <option value="Gerencia General">Gerencia General (15%)</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="fecha_negociacion" class="form-label">Fecha de Negociación *</label>
                            <input type="date" class="form-control" id="fecha_negociacion"
                                name="fecha_negociacion">
                        </div>
                    </div>

                    <!-- Paso 7: Cierre -->
                    <div class="step d-none" data-step="7">
                        <h5>Cierre</h5>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="fecha_venta" class="form-label">Fecha de Venta *</label>
                                <input type="date" class="form-control" id="fecha_venta" name="fecha_venta">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="fecha_contrato" class="form-label">Fecha de Contrato *</label>
                                <input type="date" class="form-control" id="fecha_contrato"
                                    name="fecha_contrato">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="fecha_cobro" class="form-label">Fecha de Cobro *</label>
                                <input type="date" class="form-control" id="fecha_cobro" name="fecha_cobro">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="anexo_contrato" class="form-label">Anexo de Contrato</label>
                            <input type="file" class="form-control" id="anexo_contrato" name="anexo_contrato">
                        </div>
                        <div class="mb-3">
                            <label for="metodo_pago" class="form-label">Método de Pago *</label>
                            <select class="form-select" id="metodo_pago" name="metodo_pago">
                                <option value="Efectivo">Efectivo</option>
                                <option value="Transferencia">Transferencia</option>
                                <option value="Tarjeta">Tarjeta</option>
                                <option value="Cheque">Cheque</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="monto_total" class="form-label">Monto Total *</label>
                                <input type="number" class="form-control" id="monto_total" name="monto_total"
                                    step="0.01" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="monto_pagado" class="form-label">Monto Pagado</label>
                                <input type="number" class="form-control" id="monto_pagado" name="monto_pagado"
                                    step="0.01">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="estado_final" class="form-label">Estado Final *</label>
                            <select class="form-select" id="estado_final" name="estado_final">
                                <option value="En Curso">En Curso</option>
                                <option value="Finalizada">Finalizada</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-secondary" id="prevStepBtn" disabled>Anterior</button>
                    <button type="button" class="btn btn-primary" id="nextStepBtn">Siguiente</button>
                    <button type="submit" class="btn btn-success d-none" id="submitVentaBtn">Finalizar
                        Venta</button>
                </div>
            </form>
        </div>
    </div>
</div>


@section('scripts')
    <script>
        $(document).ready(function() {
            let currentStep = 1;
            const totalSteps = 7;
            let ventaId = null;

            // Mostrar modal de creación
            $('#createVentaBtn').click(function() {
                $.get("{{ route('ventas.create') }}", function(response) {
                    if (response.success) {
                        // Reemplazar el modal con la nueva versión
                        $('#createVentaModal').replaceWith(response.html);
                        // Mostrar el modal
                        $('#createVentaModal').modal('show');
                        // Inicializar controles
                        initVentaModal();
                    } else {
                        alert('Error al cargar el formulario de venta');
                    }
                });
            });

            function initVentaModal() {
                // Manejar cambio de tipo de venta
                $('input[name="tipo_venta"]').change(function() {
                    if ($(this).val() === 'Interna') {
                        $('#clienteExistenteContainer').removeClass('d-none');
                        $('#nuevoClienteContainer').addClass('d-none');
                        $('#cliente_id').prop('required', true);
                        $('#nombre, #telefono').prop('required', false);
                    } else {
                        $('#clienteExistenteContainer').addClass('d-none');
                        $('#nuevoClienteContainer').removeClass('d-none');
                        $('#cliente_id').prop('required', false);
                        $('#nombre, #telefono').prop('required', true);
                    }
                });

                // Inicializar el estado al cargar la página
                if ($('input[name="tipo_venta"]:checked').val() === 'Interna') {
                    $('#clienteExistenteContainer').removeClass('d-none');
                    $('#nuevoClienteContainer').addClass('d-none');
                } else {
                    $('#clienteExistenteContainer').addClass('d-none');
                    $('#nuevoClienteContainer').removeClass('d-none');
                }

                // Manejar cambio de tipo de item
                $('#tipo_item_venta').change(function() {
                    if ($(this).val() === 'producto') {
                        $('#productoContainer').removeClass('d-none');
                        $('#paqueteContainer').addClass('d-none');
                        $('#paquete_id').val('');
                        $('#paquete_id').prop('required', false);

                        $('#producto_id').prop('required', true);
                    } else {
                        $('#productoContainer').addClass('d-none');
                        $('#paqueteContainer').removeClass('d-none');
                        $('#producto_id').val('');
                        $('#producto_id').prop('required', false);

                        $('#paquete_id').prop('required', true);
                    }
                });

                // Manejar cambio de cliente existente

                $('#cliente_id').change(function() {
                    const clienteId = $(this).val();
                    if (clienteId) {
                        $.get("{{ route('clientes.show', '') }}/" + clienteId, function(response) {
                            if (response.success) {
                                $('#nombre').val(response.cliente.nombre);
                                $('#telefono').val(response.cliente.telefono);
                                $('#email').val(response.cliente.email);
                                $('#direccion').val(response.cliente.direccion);
                                $('#contacto').val(response.cliente.contacto);
                            } else {
                                alert('Error al cargar los datos del cliente');
                            }
                        });
                    } else {
                        $('#nombre, #telefono, #email, #direccion, #contacto').val('');
                    }
                });

                // Manejar cambio de producto
                $('#producto_id').change(function() {
                    const precio = $(this).find(':selected').data('precio');
                    $('#monto_total').val(precio);
                });

                // Manejar cambio de paquete
                $('#paquete_id').change(function() {
                    const precio = $(this).find(':selected').data('precio');
                    $('#monto_total').val(precio);
                });



                // Manejar cambio de paso
                $('#nextStepBtn').click(function() {
                    if (currentStep < totalSteps) {
                        $(`.step[data-step="${currentStep}"]`).addClass('d-none');
                        currentStep++;
                        $(`.step[data-step="${currentStep}"]`).removeClass('d-none');
                        $('#prevStepBtn').prop('disabled', currentStep === 2);
                        $('#nextStepBtn').toggleClass('d-none', currentStep === totalSteps);
                        $('#submitVentaBtn').toggleClass('d-none', currentStep !== totalSteps);
                    }
                });

                $('#prevStepBtn').click(function() {
                    if (currentStep > 1) {
                        $(`.step[data-step="${currentStep}"]`).addClass('d-none');
                        currentStep--;
                        $(`.step[data-step="${currentStep}"]`).removeClass('d-none');
                        $('#prevStepBtn').prop('disabled', currentStep === 2);
                        $('#nextStepBtn').toggleClass('d-none', currentStep === totalSteps);
                        $('#submitVentaBtn').toggleClass('d-none', currentStep !== totalSteps);
                    }
                });

                // Manejar envío del formulario
                $('#ventaForm').submit(function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    $.ajax({
                        url: "{{ route('ventas.store') }}",
                        type: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            if (response.success) {
                                alert('Venta creada exitosamente');
                                $('#createVentaModal').modal('hide');
                                // Recargar la tabla de ventas o realizar otra acción
                            } else {
                                alert('Error al crear la venta');
                            }
                        },
                        error: function(xhr) {
                            alert('Error en el servidor: ' + xhr.responseText);
                        }
                    });
                });
            }

        });
    </script>
@endsection
