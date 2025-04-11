<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Webcoopec System LTDA.</title>



    <!-- CSS de FixedColumns -->
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/fixedcolumns/4.2.2/css/fixedColumns.dataTables.min.css">
    <!-- JS de FixedColumns -->
    <script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/4.2.2/js/dataTables.fixedColumns.min.js">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">


    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/font-awesome/4.5.0/css/font-awesome.min.css" />

    <!-- DataTable Styles -->

    <!-- jQuery (necesario para AJAX) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- SweetAlert2 para mensajes bonitos (opcional) -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">


    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- SweetAlert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <!-- En la sección <head> de tu layout o directamente en la vista -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="http://cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"></script>


    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            font-family: 'Nunito', sans-serif;
        }

        #app {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            background-color: #343a40;
            color: #fff;
            transition: width 0.3s ease;
            width: 250px;
        }

        .sidebar.collapsed {
            width: 70px;
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #495057;
        }

        .sidebar-header img {
            margin-right: 15px;
            height: 50px;
        }

        .sidebar-header span {
            font-size: 22px;
            font-weight: bold;
        }

        .sidebar.collapsed .sidebar-header span {
            display: none;
        }

        .nav-link {
            color: #fff;
            padding: 10px 15px;
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .nav-link:hover {
            background-color: #495057;
            border-radius: 4px;
        }

        .nav-link i {
            margin-right: 10px;
            font-size: 18px;
        }

        .sidebar.collapsed .nav-link span {
            display: none;
        }

        .sidebar.collapsed .nav-link i {
            margin: 0 auto;
        }

        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background-color: #0d6efd;
            width: 100%;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .container.ce {
            flex: 1;
            padding: 20px;
        }
    </style>
</head>

<body>

    <div id="app">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <img src="{{ asset('images/icono-wp.png') }}" alt="Icono">
                <span>Webcoopec System LTDA.</span>
            </div>

            <ul class="nav nav-list">
                <!-- Home Link -->
                <li class="nav-item">
                    <a class="nav-link active {{ request()->routeIs('home') ? 'active' : '' }}"
                        href="{{ route('home') }}" title="Inicio">
                        <i class="fas fa-home"></i> <span>Inicio</span>
                    </a>
                </li>

                <!-- Links for Admin -->
                @if (Auth::check() && Auth::user()->isAdmin())
                    <ul class="nav nav-list">

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('productos.index') ? 'active' : '' }}"
                                href="{{ route('productos.index') }}" title="Productos">
                                <i class="fas fa-cogs"></i> <span>Productos</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('paquetes.index') ? 'active' : '' }}"
                                href="{{ route('paquetes.index') }}" title="Paquetes">
                                <i class="fas fa-cogs"></i> <span>Paquetes</span>
                            </a>
                        </li>

                    </ul>
                @endif

                <!-- Links for Empleado -->
                @if ((Auth::check() && Auth::user()->isEmpleado()) || Auth::user()->isAsistenteGerencial())
                    <ul class="nav nav-list">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('actividades.indexActividades') ? 'active' : '' }}"
                                href="{{ route('actividades.indexActividades') }}" title="Actividades">
                                <i class="bi bi-journal-check"></i> <span>Actividades</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('daily.index') ? 'active' : '' }}"
                                href="{{ route('daily.index') }}" title="Daily Scrum">
                                <i class="bi bi-ubuntu"></i> <span>Daily Scrum</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('permisos.index') ? 'active' : '' }}"
                                href="{{ route('permisos.index') }}" title="Permisos">
                                <i class="bi bi-person-walking"></i> <span>Permisos</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('vacaciones.index') ? 'active' : '' }}"
                                href="{{ route('vacaciones.index') }}" title="Vacaciones">
                                <i class="bi bi-calendar2-week"></i> <span>Vacaciones</span>
                            </a>

                        </li>


                    </ul>
                @endif

                <!-- Links for Supervisor -->
                @if (Auth::check() && Auth::user()->isEmpleado() && Auth::user()->empleado->es_supervisor)
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('matriz_Cumplimientos.index') ? 'active' : '' }}"
                            href="{{ route('matriz_cumplimientos.index') }}" title="Matriz de Cumplimiento">
                            <i class="bi bi-table"></i> <span>Matriz de Cumplimiento</span>
                        </a>
                    </li>
                @endif
                @if (Auth::check() &&
                        Auth::user()->empleado &&
                        (Auth::user()->empleado->cargo_id == 15 || Auth::user()->empleado->cargo_id == 14))
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('ventas.index') ? 'active' : '' }}"
                            href="{{ route('ventas.index') }}" title="Ventas">
                            <i class="bi bi-cart"></i> <span>Ventas</span>
                        </a>
                    </li>
                @endif
                @if (Auth::check() && Auth::user()->isGerenteGeneral())
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('analisis.indexAnalisis') ? 'active' : '' }}"
                            href="{{ route('analisis.indexAnalisis') }}" title="Inteligencia de Negocios">
                            <i class="bi bi-bar-chart"></i> <span>Inteligencia de Negocios</span>
                        </a>

                    </li>
                @endif



            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">


            <nav class="navbar navbar-expand-md navbar-light shadow-sm">

                <button class="btn btn-primary sidebar-toggler" id="sidebarToggler">
                    <i class="fa fa-bars"></i>
                </button>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/') }}">{{ __('Login') }}</a>
                                </li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Registrarse') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="#"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Salir</a>
                                    </li>
                                </ul>
                            </li>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>
                        @endguest
                    </ul>
                </div>

            </nav>

            <main class="py-4">
                <div class="container ce">
                    @yield('content')
                </div>
            </main>

        </div>




    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggler = document.getElementById('sidebarToggler');

            // Recuperar el estado del sidebar desde localStorage
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (isCollapsed) {
                sidebar.classList.add('collapsed');
            }

            sidebarToggler.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
                // Guardar el estado del sidebar en localStorage
                localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
            });
        });
    </script>
</body>

</html>
