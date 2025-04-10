@extends('layouts.rrhh')

@section('content')
    <div class="home-background"></div>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header text-center">
                        <h2 style="color: white">Recursos Humanos</h2>
                    </div>
                    <div class="card-body">
                        <div class="row justify-content-center text-center">

                            <div class="col-md-5 mb-5 center">
                                <div class="card h-100 shadow   center">
                                    <div class="card-body d-flex flex-column align-items-center">
                                        <a href="{{ route('empleados.indexEmpleados') }}">
                                            <img src="{{ asset('images/clientes.png') }}" alt="Empleados"
                                                class="img-fluid mb-2" style="width: 100px; height: 100px;">
                                            <h5 class="card-title" style="color: white">Empleados</h5>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-5 mb-5 center">
                                <div class="card h-100 shadow   center">
                                    <div class="card-body d-flex flex-column align-items-center">
                                        <a href="{{ route('roles_pago.index') }}">
                                            <img src="{{ asset('images/rol-de-pagos.png') }}" alt="Clientes"
                                                class="img-fluid mb-2" style="width: 100px; height: 100px;">
                                            <h5 class="card-title" style="color: white">Rol de Pago</h5>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-5 mb-5 center">
                                <div class="card h-100 shadow   center">
                                    <div class="card-body d-flex flex-column align-items-center">
                                        <a href="{{ route('supervisores.index') }}">
                                            <img src="{{ asset('images/supervisor.png') }}" alt="Clientes"
                                                class="img-fluid mb-2" style="width: 100px; height: 100px;">
                                            <h5 class="card-title" style="color: white">Supervisores</h5>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-5 mb-5 center">
                                <div class="card h-100 shadow   center">
                                    <div class="card-body d-flex flex-column align-items-center">
                                        <a href="{{ route('departamentos.index') }}">
                                            <img src="{{ asset('images/departamentos.png') }}" alt="Clientes"
                                                class="img-fluid mb-2" style="width: 100px; height: 100px;">
                                            <h5 class="card-title" style="color: white">Departamentos</h5>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-5 mb-5 center">
                                <div class="card h-100 shadow   center">
                                    <div class="card-body d-flex flex-column align-items-center">
                                        <a href="{{ route('cargos.index') }}">
                                            <img src="{{ asset('images/cargos.png') }}" alt="Clientes"
                                                class="img-fluid mb-2" style="width: 100px; height: 100px;">
                                            <h5 class="card-title" style="color: white">Cargos</h5>
                                        </a>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-5 mb-5 center">
                                <div class="card h-100 shadow   center">
                                    <div class="card-body d-flex flex-column align-items-center">
                                        <a href="{{ route('rubros.index') }}">
                                            <img src="{{ asset('images/cobros.png') }}" alt="Clientes"
                                                class="img-fluid mb-2" style="width: 100px; height: 100px;">
                                            <h5 class="card-title" style="color: white">Rubros</h5>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-5 mb-5 center">
                                <div class="card h-100 shadow   center">
                                    <div class="card-body d-flex flex-column align-items-center">
                                        <a href="{{ route('vacaciones.index') }}">
                                            <img src="{{ asset('images/ventas.png') }}" alt="Clientes"
                                                class="img-fluid mb-2" style="width: 100px; height: 100px;">
                                            <h5 class="card-title" style="color: white">Vacaciones</h5>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-5 mb-5 center">
                                <div class="card h-100 shadow   center">
                                    <div class="card-body d-flex flex-column align-items-center">
                                        <a href="{{ route('permisos.index') }}">
                                            <img src="{{ asset('images/ventas.png') }}" alt="Clientes"
                                                class="img-fluid mb-2" style="width: 100px; height: 100px;">
                                            <h5 class="card-title" style="color: white">Permisos</h5>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-5 mb-5 center">
                                <div class="card h-100 shadow   center">
                                    <div class="card-body d-flex flex-column align-items-center">
                                        <a href="{{ route('matriz_cumplimientos.index') }}">
                                            <img src="{{ asset('images/ventas.png') }}" alt="Clientes"
                                                class="img-fluid mb-2" style="width: 100px; height: 100px;">
                                            <h5 class="card-title" style="color: white">Matriz de Cumplimiento</h5>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-5 mb-5 center">
                                <div class="card h-100 shadow   center">
                                    <div class="card-body d-flex flex-column align-items-center">
                                        <a href="{{ route('parametros.index') }}">
                                            <img src="{{ asset('images/parametros.png') }}" alt="Clientes"
                                                class="img-fluid mb-2" style="width: 100px; height: 100px;">
                                            <h5 class="card-title" style="color: white">Parametros</h5>
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .home-background {
            background-image: url('{{ asset('images/nova-tics-fondo.jpg') }}');
            /* Ruta de la imagen */
            background-size: cover;
            /* Cubrir todo el contenedor */
            background-position: center;
            /* Centrar la imagen */
            background-repeat: no-repeat;
            /* Evitar que se repita */
            min-height: 100vh;
            /* Altura mínima del 100% de la ventana */
            position: fixed;
            /* Fijar el fondo */
            top: 0;
            left: 0;
            width: 100%;
            z-index: -1;
            /* Enviar al fondo */
            opacity: 0.9;
        }

        .card {
            border-radius: 10px;
            background-color: #3a92c6ba;
            opacity: 19;


        }
    </style>
@endsection
