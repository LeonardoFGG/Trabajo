@extends('layouts.app')

@section('content')
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


    <div class="home-background"></div>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header text-center">
                        <h2 style="color: white">Dashboard</h2>
                    </div>
                    <div class="card-body ">
                        <div class="row justify-content-center text-center">
                            @if (Auth::user()->isAdmin())
                                @foreach ([
            ['route' => 'rrhh.index', 'img' => 'rrhh.png', 'title' => 'RRHH'],
            ['route' => 'empleados.indexEmpleados', 'img' => 'activos.png', 'title' => 'Activos'],
            ['route' => 'actividades.indexActividades', 'img' => 'actividades.png', 'title' => 'Actividades'],
            ['route' => 'clientes.index', 'img' => 'cooperativas.png', 'title' => 'Clientes'],
            ['route' => 'empleados.indexEmpleados', 'img' => 'cobros.png', 'title' => 'Cobros'],
            ['route' => 'empleados.indexEmpleados', 'img' => 'mensajeria.png', 'title' => 'Mensajería'],
            ['route' => 'productos.index', 'img' => 'productos.png', 'title' => 'Productos'],
            ['route' => 'empleados.indexEmpleados', 'img' => 'seguridad.png', 'title' => 'Seguridades'],
            ['route' => 'ventas.index', 'img' => 'ventas.png', 'title' => 'Ventas'],
            ['route' => 'empleados.indexEmpleados', 'img' => 'rrhh.png', 'title' => 'Usuarios'],
            ['route' => 'analisis.indexAnalisis', 'img' => 'inteligencia-de-negocios.png', 'title' => 'Inteligencia de Negocios'],
            ['route' => 'daily.index', 'img' => 'scrum.png', 'title' => 'Daily Scrum'],
            ['route' => 'imagen.index', 'img' => 'ventas.png', 'title' => 'Anuncio'],
        ] as $item)
                                    <div class="col-md-3 mb-4">
                                        <div class="card h-100 shadow">
                                            <div class="card-body d-flex flex-column align-items-center">
                                                <a href="{{ route($item['route']) }}">
                                                    <img src="{{ asset('images/' . $item['img']) }}"
                                                        alt="{{ $item['title'] }}" class="img-fluid mb-2"
                                                        style="width: 60px; height: 60px;">
                                                    <h5 class="card-title"> <span style="color: white;">
                                                            {{ $item['title'] }}</h5> </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @elseif (Auth::user()->isEmpleado())
                                <div class="col-md-5 mb-5 center">
                                    <div class="card h-100 shadow   center">
                                        <div class="card-body d-flex flex-column align-items-center">
                                            <a href="{{ route('actividades.indexActividades') }}">
                                                <i class="bi bi-journal-check" style="font-size: 100px; color: white;"></i>
                                                <h5 class="card-title " style="color: white">Actividades</h5>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-5 mb-5 center">
                                    <div class="card h-100 shadow center">
                                        <div class="card-body d-flex flex-column align-items-center">
                                            <a href="{{ route('daily.index') }}">
                                                <i class="bi bi-ubuntu" style="font-size: 100px; color: white;"></i>

                                                <h5 class="card-title" style="color: white">Daily Scrum</h5>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-5 mb-5 center">
                                    <div class="card h-100 shadow   center">
                                        <div class="card-body d-flex flex-column align-items-center">
                                            <a href="{{ route('permisos.index') }}">
                                                <i class="bi bi-person-walking"  style="font-size: 100px; color: white;"></i>
                                                <h5 class="card-title" style="color: white">Permisos</h5>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                            
                                <div class="col-md-5 mb-5 center">
                                    <div class="card h-100 shadow center">
                                        <div class="card-body d-flex flex-column align-items-center">
                                            <a href="{{ route('vacaciones.index') }}">
                                                <i class="bi bi-calendar2-week" style="font-size: 100px; color: white;"></i>
                                                <h5 class="card-title" style="color: white">Vacaciones</h5>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                            @endif
                            @if (Auth::user()->isEmpleado() && Auth::user()->empleado->es_supervisor)
                                <div class="col-md-5 mb-5 center">
                                    <div class="card h-100 shadow center">
                                        <div class="card-body d-flex flex-column align-items-center">
                                            <a href="{{ route('matriz_cumplimientos.index') }}">
                                                <i class="bi bi-table" style="font-size: 100px; color: white;"></i>
                                                <h5 class="card-title" style="color: white">Matriz de Cumplimientos</h5>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if (Auth::user()->id == 8)
                                <div class="col-md-5 mb-5 center">
                                    <div class="card h-100 shadow center">
                                        <div class="card-body d-flex flex-column align-items-center">
                                            <a href="{{ route('imagen.index') }}">
                                                <i class="bi bi-megaphone" style="font-size: 100px; color: white;"></i>
                                                <h5 class="card-title" style="color: white">Anuncios</h5>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            {{-- @if (Auth::user()->empleado && (Auth::user()->empleado->cargo_id == 15 || Auth::user()->empleado->cargo_id == 14))
                                <div class="col-md-5 mb-5 center">
                                    <div class="card h-100 shadow center">
                                        <div class="card-body d-flex flex-column align-items-center">
                                            <a href="{{ route('ventas.index') }}">
                                                <i class="bi bi-cart" style="font-size: 100px; color: white;"></i>
                                                <h5 class="card-title" style="color: white">Ventas</h5>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif --}}
                            @if (Auth::user()->isGerenteGeneral() || Auth::user()->isSupervisor()) 
                                <div class="col-md-5 mb-5 center">
                                    <div class="card h-100 shadow center">
                                        <div class="card-body d-flex flex-column align-items-center">
                                            <a href="{{ route('analisis.indexAnalisis') }}">
                                                <i class="bi bi-bar-chart" style="font-size: 100px; color: white;"></i>
                                                <h5 class="card-title" style="color: white">Inteligencia de Negocios</h5>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endsection
