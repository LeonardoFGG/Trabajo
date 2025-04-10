<?php
/*****************************************************
 * Nombre del Proyecto: ERP 
 * Descripción: Manejador de excepciones
 * Modulo: Exceptions
 * Version: 1.0
 * Desarrollado por: Karol Macas
 * Fecha de Inicio: 21/09/2024
 * Ultima Modificación: 18/10/2024
 ****************************************************/
namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Session\TokenMismatchException;


class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        // Manejar error 404 (Modelo no encontrado)
        if ($exception instanceof ModelNotFoundException) {
            return response()->view('errors.404', [], 404);
        }

        // Manejar error 500 (Error del servidor)
        if ($exception instanceof HttpException && $exception->getStatusCode() == 500) {
            return response()->view('errors.500', [], 500);
        }

        if ($exception instanceof TokenMismatchException) {
            // Redirigir al usuario a una vista específica si ocurre un error 419 (sesión expirada)
            return redirect()->route('welcome') // Redirige a la ruta de login o donde desees
                         ->with('error', 'Tu sesión ha expirado. Por favor, vuelve a iniciar sesión.');
        }

        return parent::render($request, $exception);
    }
}
