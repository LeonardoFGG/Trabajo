<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckTime
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
        // Aquí puedes implementar cualquier lógica personalizada
        // Por ahora, simplemente deja que la solicitud continúe
        return $next($request);
    }
}
