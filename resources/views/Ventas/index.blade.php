<!-- resources/views/ventas/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-between mb-4">
        <div class="col-md-6">
            <h1>Gestión de Ventas</h1>
        </div>
        <div class="col-md-6 text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createVentaModal">
                <i class="fas fa-plus"></i> Nueva Venta
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Estadísticas de Ventas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Ventas Activas</h5>
                    <h2>{{ $ventas->where('estado', 'Activa')->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <h5 class="card-title">Ventas Pausadas</h5>
                    <h2>{{ $ventas->where('estado', 'Pausada')->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Ventas Finalizadas</h5>
                    <h2>{{ $ventas->where('estado', 'Finalizada')->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5 class="card-title">Ventas Canceladas</h5>
                    <h2>{{ $ventas->where('estado', 'Cancelada')->count() }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel Visual del Workflow -->
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">Workflow de Ventas</h5>
        </div>
        <div class="card-body">
            <div class="workflow-container">
                <div class="workflow-steps d-flex flex-nowrap overflow-auto">
                    <div class="step active" data-estado="Prospección">
                        <div class="step-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <div class="step-label">Prospección</div>
                        <div class="step-count">{{ $ventas->where('estado_comercial', 'Prospección')->count() }}</div>
                    </div>
                    <div class="step-connector align-self-center"></div>
                    <div class="step" data-estado="Contacto">
                        <div class="step-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="step-label">Contacto</div>
                        <div class="step-count">{{ $ventas->where('estado_comercial', 'Contacto')->count() }}</div>
                    </div>
                    <div class="step-connector align-self-center"></div>
                    <div class="step" data-estado="Presentación">
                        <div class="step-icon">
                            <i class="fas fa-file-powerpoint"></i>
                        </div>
                        <div class="step-label">Presentación</div>
                        <div class="step-count">{{ $ventas->where('estado_comercial', 'Presentación')->count() }}</div>
                    </div>
                    <div class="step-connector align-self-center"></div>
                    <div class="step" data-estado="Propuesta">
                        <div class="step-icon">
                            <i class="fas fa-file-contract"></i>
                        </div>
                        <div class="step-label">Propuesta</div>
                        <div class="step-count">{{ $ventas->where('estado_comercial', 'Propuesta')->count() }}</div>
                    </div>
                    <div class="step-connector align-self-center"></div>
                    <div class="step" data-estado="Negociación">
                        <div class="step-icon">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <div class="step-label">Negociación</div>
                        <div class="step-count">{{ $ventas->where('estado_comercial', 'Negociación')->count() }}</div>
                    </div>
                    <div class="step-connector align-self-center"></div>
                    <div class="step" data-estado="Cierre">
                        <div class="step-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="step-label">Cierre</div>
                        <div class="step-count">{{ $ventas->where('estado_comercial', 'Cierre')->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    <!-- Tabla de Ventas con Tabs -->
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="ventasTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="todas-tab" data-bs-toggle="tab" href="#todas" role="tab" aria-controls="todas" aria-selected="true">
                        Todas
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="pendientes-tab" data-bs-toggle="tab" href="#pendientes" role="tab" aria-controls="pendientes" aria-selected="false">
                        Pendientes
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="activas-tab" data-bs-toggle="tab" href="#activas" role="tab" aria-controls="activas" aria-selected="false">
                        Activas
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="pausadas-tab" data-bs-toggle="tab" href="#pausadas" role="tab" aria-controls="pausadas" aria-selected="false">
                        Pausadas
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="finalizadas-tab" data-bs-toggle="tab" href="#finalizadas" role="tab" aria-controls="finalizadas" aria-selected="false">
                        Finalizadas
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="ventasTabsContent">
                <div class="tab-pane fade show active" id="todas" role="tabpanel" aria-labelledby="todas-tab">
                    @include('ventas.partials.ventas-table', ['ventas' => $ventas])
                </div>
                <div class="tab-pane fade" id="pendientes" role="tabpanel" aria-labelledby="pendientes-tab">
                    @include('ventas.partials.ventas-table', ['ventas' => $ventas->where('estado', 'Pendiente')])
                </div>
                <div class="tab-pane fade" id="activas" role="tabpanel" aria-labelledby="activas-tab">
                    @include('ventas.partials.ventas-table', ['ventas' => $ventas->where('estado', 'Activa')])
                </div>
                <div class="tab-pane fade" id="pausadas" role="tabpanel" aria-labelledby="pausadas-tab">
                    @include('ventas.partials.ventas-table', ['ventas' => $ventas->where('estado', 'Pausada')])
                </div>
                <div class="tab-pane fade" id="finalizadas" role="tabpanel" aria-labelledby="finalizadas-tab">
                    @include('ventas.partials.ventas-table', ['ventas' => $ventas->where('estado', 'Finalizada')])
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-3">
        {{ $ventas->links() }}
    </div>
</div>

<style>
    .workflow-steps {
        display: flex;
        flex-wrap: nowrap;
        overflow-x: auto;
        padding-bottom: 10px;
    }
    .step {
        display: inline-flex;
        flex-direction: column;
        align-items: center;
        min-width: 100px;
        padding: 10px;
        position: relative;
    }
    .step-connector {
        flex: 1;
        height: 2px;
        background-color: #dee2e6;
        min-width: 20px;
        margin: 0 5px;
        align-self: center;
    }
    .step-icon {
        font-size: 1.5rem;
        margin-bottom: 5px;
    }
    .step-label {
        font-size: 0.85rem;
        text-align: center;
        margin-bottom: 5px;
    }
    .step-count {
        background-color: #f8f9fa;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
    }

    .step.active .step-icon {
        color: #007bff;
    }

    .step.active .step-label {
        font-weight: bold;
    }

    .step.active .step-count {
        background-color: #007bff;
        color: white;
    }
    
   
    </style>


<!-- Modal para crear venta -->
@include('ventas.partials.create-modal')

<!-- Modal para workflow -->
@include('ventas.partials.workflow-modal')

@endsection

@section('styles')
<style>
   
    
    /* Para los estados de las ventas */
    .badge.bg-secondary { opacity: 0.8; }
    .badge.bg-success { opacity: 0.8; }
    .badge.bg-warning { opacity: 0.8; }
    .badge.bg-danger { opacity: 0.8; }
    .badge.bg-info { opacity: 0.8; }
    .badge.bg-primary { opacity: 0.8; }
    .badge.bg-dark { opacity: 0.8; }
    
    /* Mejorar tabla */
    .table th {
        background-color: #f8f9fa;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Script para manejar la selección de tipo de item de venta
        const tipoItemVentaSelect = document.getElementById('tipo_item_venta');
        const productoSection = document.getElementById('producto_section');
        const paqueteSection = document.getElementById('paquete_section');
        
        if (tipoItemVentaSelect) {
            tipoItemVentaSelect.addEventListener('change', function() {
                if (this.value === 'producto') {
                    productoSection.classList.remove('d-none');
                    paqueteSection.classList.add('d-none');
                } else if (this.value === 'paquete') {
                    productoSection.classList.add('d-none');
                    paqueteSection.classList.remove('d-none');
                } else {
                    productoSection.classList.add('d-none');
                    paqueteSection.classList.add('d-none');
                }
            });
        }
        
        // Script para abrir modal de workflow con datos de venta
        const workflowModal = document.getElementById('workflowModal');
        if (workflowModal) {
            workflowModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const ventaId = button.getAttribute('data-venta-id');
                const ventaEstado = button.getAttribute('data-venta-estado');
                const ventaEstadoComercial = button.getAttribute('data-venta-estado-comercial');
                
                // Actualizar formulario con datos de la venta
                const form = workflowModal.querySelector('form');
                form.action = `/ventas/${ventaId}/avanzar-estado`;
                
                // Establecer estado actual
                const estadoActualElement = workflowModal.querySelector('#estado_actual');
                estadoActualElement.textContent = ventaEstadoComercial;
                
                // Preparar formulario según estado comercial actual
                prepararFormularioWorkflow(ventaEstadoComercial);
            });
        }
        
        function prepararFormularioWorkflow(estadoActual) {
            // Ocultar todos los formularios específicos de estado
            document.querySelectorAll('.estado-form').forEach(form => {
                form.classList.add('d-none');
            });
            
            // Determinar próximo estado posible
            let proximoEstado = '';
            switch(estadoActual) {
                case 'Prospección':
                    proximoEstado = 'Contacto';
                    break;
                case 'Contacto':
                    proximoEstado = 'Presentación';
                    break;
                case 'Presentación':
                    proximoEstado = 'Propuesta';
                    break;
                case 'Propuesta':
                    proximoEstado = 'Negociación';
                    break;
                case 'Negociación':
                    proximoEstado = 'Cierre';
                    break;
                case 'Cierre':
                    // No hay próximo estado después de Cierre
                    document.querySelector('#no_next_state').classList.remove('d-none');
                    return;
            }
            
            // Mostrar formulario del próximo estado
            document.querySelector(`#${proximoEstado.toLowerCase()}_form`).classList.remove('d-none');
            document.querySelector('#nuevo_estado').value = proximoEstado;
        }
        
        // Filtrado rápido por etapa del workflow al hacer clic en los íconos
        const workflowSteps = document.querySelectorAll('.workflow-steps .step');
        if (workflowSteps.length > 0) {
            workflowSteps.forEach(step => {
                step.addEventListener('click', function() {
                    const estado = this.getAttribute('data-estado');
                    filterByWorkflowState(estado);
                });
            });
        }
        
        function filterByWorkflowState(estado) {
            // Aquí podrías implementar un filtrado AJAX o simplemente mostrar una alerta
            alert(`Filtrando por estado comercial: ${estado}`);
            // También podrías redirigir a una URL con el filtro aplicado:
            // window.location.href = '/ventas?estado_comercial=' + encodeURIComponent(estado);
        }
        
        // Checkbox para productos y paquetes
        document.querySelectorAll('input[id^="producto_check_"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const id = this.id.replace('producto_check_', '');
                const cantidadInput = document.getElementById(`producto_cantidad_${id}`);
                
                if (this.checked) {
                    cantidadInput.value = 1;
                } else {
                    cantidadInput.value = 0;
                }
            });
        });
        
        document.querySelectorAll('input[id^="paquete_check_"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const id = this.id.replace('paquete_check_', '');
                const cantidadInput = document.getElementById(`paquete_cantidad_${id}`);
                
                if (this.checked) {
                    cantidadInput.value = 1;
                } else {
                    cantidadInput.value = 0;
                }
            });
        });
    });
</script>
@endsection