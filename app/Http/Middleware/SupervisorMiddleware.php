<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupervisorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

    public function handle($request, Closure $next)
     {
         $user = Auth::user();
     
         if ($user && $user->role === 'admin') {
             return $next($request);
         }
     
         if ($user && $user->empleado) {
             // Permitir acceso si es supervisor
             if ($user->empleado->es_supervisor) {
                 return $next($request);
             }
     
             // Permitir acceso si es un empleado normal
             return $next($request);
         }
     
         abort(403, 'No tienes permisos para acceder a esta página');
     }
}