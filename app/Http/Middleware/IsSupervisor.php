<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Supervisor;

class IsSupervisor
{
    public function handle($request, Closure $next)
    {
        $empleado = auth()->user()->empleado;
    
        if (!$empleado || !$empleado->supervisor) {
            abort(403, 'Acceso denegado.');
        }
    
        return $next($request);
    }
}
