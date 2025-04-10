<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PasswordExpired
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Aquí puedes implementar lógica para verificar contraseñas expiradas
        // Por ejemplo:
        // if ($request->user() && $request->user()->password_expired) {
        //     return redirect()->route('password.reset');
        // }

        return $next($request);
    }
}
