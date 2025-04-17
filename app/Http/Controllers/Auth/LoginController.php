<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\Empleados;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Redirección después de iniciar sesión.
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Middleware.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Validación adicional: Verificar si el usuario está activo en empleados.
     */
   protected function authenticated(Request $request, $user)
{
    // Si el usuario es administrador, puede ingresar directamente
    if ($user->role === 'admin') {
        return; // Sigue su camino normal
    }

    // Para otros usuarios, validamos su estado en la tabla empleados
    $empleado = Empleados::where('correo_institucional', $user->email)->first();

    if (!$empleado || $empleado->estado != 1) {
        auth()->logout();

        return redirect('/login')->withErrors([
            'email' => 'Tu cuenta está inactiva o no tienes permisos de acceso.',
        ]);
    }
}

}
