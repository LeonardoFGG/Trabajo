<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckUserById
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    public function handle($request, Closure $next, $key, $value)
    {
        $allowedUserId = (int) $value; // Convierte el ID permitido a entero
        $user = Auth::user();

        // Permitir acceso si el usuario es administrador o tiene el ID permitido
        if ($user && ($user->id === $allowedUserId || $user->role === 'admin')) {
            return $next($request);
        }

        return redirect('/welcome')->withErrors('No tienes permiso para acceder a esta página.');
    }
}
