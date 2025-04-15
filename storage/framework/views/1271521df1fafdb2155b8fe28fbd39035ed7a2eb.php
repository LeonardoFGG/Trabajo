<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Webcoopec System LTDA.</title>

    <!-- Cargar la librerÃ­a CSS de Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <!-- Cargar la librerÃ­a JS de Flatpickr -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- CSS de FixedColumns -->
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/fixedcolumns/4.2.2/css/fixedColumns.dataTables.min.css">
    <!-- JS de FixedColumns -->
    <script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/4.2.2/js/dataTables.fixedColumns.min.js">
    </script>


    <!-- Scripts -->
    <script src="<?php echo e(asset('js/app.js')); ?>" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="<?php echo e(asset('css/app.css')); ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">


    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/font-awesome/4.5.0/css/font-awesome.min.css" />

    <!-- DataTable Styles -->

    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">


    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- SweetAlert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <!-- En la secciÃ³n <head> de tu layout o directamente en la vista -->
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
                <img src="<?php echo e(asset('images/icono-wp.png')); ?>" alt="Icono">
                <span>Webcoopec System LTDA.</span>
            </div>

            <ul class="nav nav-list">
                <!-- Home Link -->
                <li class="nav-item">
                    <a class="nav-link active <?php echo e(request()->routeIs('home') ? 'active' : ''); ?>"
                        href="<?php echo e(route('home')); ?>" title="Inicio">
                        <i class="fas fa-home"></i> <span>Inicio</span>
                    </a>
                </li>

                <?php if((Auth::check() && Auth::user()->isAdmin()) || Auth::user()->empleado->es_supervisor|| (Auth::check() && Auth::user()->isGerenteGeneral()) || (Auth::check() &&  Auth::user()->isAsistenteGerencial()) ): ?>
                    <ul class="nav nav-list">
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('permisos.index') ? 'active' : ''); ?>"
                                href="<?php echo e(route('permisos.index')); ?>" title="Permisos">
                                <i class="fas fa-user-lock"></i> <span>Permisos</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('permisos.indexHoras') ? 'active' : ''); ?>"
                                href="<?php echo e(route('permisos.indexHoras')); ?>" title="Permisos de Horas">
                                <i class="fas fa-user-clock"></i> <span>Permisos de Horas del Empleado</span>
                            </a>

                    </ul>
                <?php endif; ?>


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
                    aria-expanded="false" aria-label="<?php echo e(__('Toggle navigation')); ?>">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto">
                        <?php if(auth()->guard()->guest()): ?>
                            <?php if(Route::has('login')): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo e(url('/')); ?>"><?php echo e(__('Login')); ?></a>
                                </li>
                            <?php endif; ?>
                            <?php if(Route::has('register')): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo e(route('register')); ?>"><?php echo e(__('Registrarse')); ?></a>
                                </li>
                            <?php endif; ?>
                        <?php else: ?>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <?php echo e(Auth::user()->name); ?>

                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="#"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Salir</a>
                                    </li>
                                </ul>
                            </li>

                            <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                                <?php echo csrf_field(); ?>
                            </form>
                        <?php endif; ?>
                    </ul>
                </div>

            </nav>

            <main class="py-4">
                <div class="container ce">
                    <?php echo $__env->yieldContent('content'); ?>
                </div>
            </main>

        </div>




    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
    <script src="<?php echo e(asset('js/app.js')); ?>" defer></script>

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

</html><?php /**PATH C:\Users\WebCoop_Jhoa\Documents\repositorio\Mio\Trabajo\resources\views/layouts/permisos.blade.php ENDPATH**/ ?>