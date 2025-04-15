

<?php $__env->startSection('content'); ?>
    <div class="container d-flex flex-column justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="text-center">
            <!-- Imagen de error 404 (puedes usar cualquier imagen o ícono) -->
            <img src="https://media.giphy.com/media/C96LIhwcVJC3HhCXEB/giphy.gif" alt="404 Error"
                style="max-width: 150px; animation: bounce 2s infinite;">

            <!-- Encabezado con estilo personalizado -->
            <h1 class="display-4 font-weight-bold">¡Oops! Página no encontrada</h1>
            <p class="lead text-muted">Parece que la página que estás buscando no existe o fue movida.</p>

            <!-- Botón para volver al inicio -->
            <a href="<?php echo e(url('/')); ?>" class="btn btn-lg btn-primary mt-4 px-5">
                <i class="fas fa-home"></i> Volver al Inicio
            </a>
        </div>
    </div>

    <!-- Estilos adicionales para animación y estética -->
    <style>
        @keyframes  bounce {

            0%,
            20%,
            50%,
            80%,
            100% {
                transform: translateY(0);
            }

            40% {
                transform: translateY(-30px);
            }

            60% {
                transform: translateY(-15px);
            }
        }

        .container {
            color: #333;
        }

        h1 {
            color: #007bff;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
        }

        .btn-primary {
            border-radius: 50px;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\WebCoop_Jhoa\Documents\repositorio\Mio\Trabajo\resources\views/errors/404.blade.php ENDPATH**/ ?>