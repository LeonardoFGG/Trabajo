
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Webcoopes System</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/styles.css')); ?>">
</head>

<body>
    <div class="login-wrap">
        <form method="POST" action="<?php echo e(route('login')); ?>">
            <?php echo csrf_field(); ?>
            <div class="login-html">
                <input id="tab-1" type="radio" name="tab" class="sign-in" checked><label for="tab-1"
                    class="tab">Iniciar Sesión</label>
                <input id="tab-2" type="radio" name="tab" class="sign-up"><label for="tab-2"
                    class="tab">Registrarse</label>
                <div class="login-form">
                    <div class="sign-in-htm">
                        <div class="group">
                            <label for="email" class="label">Usuario</label>
                            <input id="email" type="text" class="input <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                name="email" value="<?php echo e(old('email')); ?>" required autocomplete="email" autofocus>
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback" role="alert">
                                    <strong><?php echo e($message); ?></strong>
                                </span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="group">
                            <label for="password" class="label">Contraseña</label>
                            <input id="password" type="password" class="input <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                name="password" required autocomplete="current-password" data-type="password">
                            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback" role="alert">
                                    <strong><?php echo e($message); ?></strong>
                                </span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="group">
                            <input id="check" type="checkbox" class="check" checked>
                            <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                <?php echo e(old('remember') ? 'checked' : ''); ?>>
                            <label for="check" class="remember-label1"> Recordar Contraseña</label>
                        </div>
                        <div class="group">
                            <input type="submit" class="button" value="Ingresar">
                        </div>
                        <div class="hr"></div>
                        <div class="foot-lnk">
                            <a class="remember-label" href="<?php echo e(route('password.request')); ?>">Olvidé mi Contraseña</a>
                        </div>
                    </div>
                    <div class="sign-up-htm">
                        <div class="group">
                            <p class="messageRegister">
                                Por favor, comuníquese con el área de Seguridad de la Información para proceder con su
                                registro o enviar su solicitud al correo:
                                <a href="mailto:santiagog@webcoopec.com">santiagog@webcoopec.com</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </form>


    </div>
    
    
    <div class="overlay" id="overlay">
        <div class="overlay-content">
            <button class="close-btn" id="closeBtn">X</button>
            <?php if(isset($imagen)): ?>
                <img width="50%" height="50%" src="<?php echo e(asset('uploads/' . $imagen->file_path)); ?>" alt="Notificación de actualización">
            <?php else: ?>
                <img width="50%" height="50%" src="<?php echo e(asset('images/portada.gif')); ?>" alt="Notificación de actualización">
            <?php endif; ?>
        </div>
    </div>
    

    <script>
        // Cierra el overlay cuando el usuario hace clic en la "X"
        document.getElementById("closeBtn").addEventListener("click", function() {
            document.getElementById("overlay").style.display = "none";
        });

        // Cierra el overlay automáticamente después de 5 segundos
        // setTimeout(function() {
        //     document.getElementById("overlay").style.display = "none";
        // }, 5000); // 5000 milisegundos = 5 segundos
    </script>


</body>

</html>
<?php /**PATH C:\Users\WebCoop_Jhoa\Documents\repositorio\Mio\Trabajo\resources\views/welcome.blade.php ENDPATH**/ ?>