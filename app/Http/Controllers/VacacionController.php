<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SolicitudVacaciones;
use App\Models\Vacacion;
use App\Models\Empleados;
use Illuminate\Support\Facades\Auth;
use Laravel\Ui\Presets\React;
use Carbon\Carbon;

class VacacionController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();
        // Obtener el ID del empleado seleccionado y los filtros
        $empleadoId = $request->input('empleado_id');
        // Obtener las fechas de inicio y fin del rango
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        // Obtener las fechas de inicio y fin del rango
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Si no se ha seleccionado un rango, establecemos las fechas por defecto (hoy)
        $startDate = $startDate ? $startDate : now()->toDateString();
        $endDate = $endDate ? $endDate : now()->toDateString();
                

        // Obtener el saldo de vacaciones del empleado (si es empleado)
        $saldo = null;
        if ($user->isEmpleado()) {
            $saldo = Vacacion::where('empleado_id', $user->empleado->id)->first();
        }

        // Consulta base para las solicitudes de vacaciones
        $solicitudesQuery = SolicitudVacaciones::with(['empleado', 'aprobadoPor']);

        // Filtros según el rol del usuario
        if ($user->isAdmin() || $user->isGerenteGeneral() || $user->isAsistenteGerencial()) {
            // Admin ve todas las solicitudes, pero puede filtrar por empleado
            $solicitudesQuery->when($empleadoId, function ($query) use ($empleadoId) {
                return $query->where('empleado_id', $empleadoId);
            });

            $empleados = Empleados::all(); // Todos los empleados para el filtro

        } elseif ($user->isSupervisor()) {
            // Supervisor ve las solicitudes de los empleados a su cargo y las suyas propias
            $solicitudesQuery->where(function ($query) use ($user, $empleadoId) {
                $query->whereHas('empleado', function ($subQuery) use ($user) {
                    $subQuery->where('supervisor_id', $user->empleado->id);
                })->orWhere('empleado_id', $user->empleado->id);
            });

            // Filtrar por empleado si se selecciona uno
            if ($empleadoId) {
                $solicitudesQuery->where('empleado_id', $empleadoId);
            }

            $empleados = Empleados::where('supervisor_id', $user->empleado->id)->get();
        } elseif ($user->isEmpleado()) {
            // Empleado ve solo sus propias solicitudes
            $solicitudesQuery->where('empleado_id', $user->empleado->id);
            $empleados = collect(); // No necesita empleados para el filtro
        } else {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta página');
        }

        // Filtrar por rango de fechas
        if ($startDate && $endDate) {
            $solicitudesQuery->whereBetween('fecha_solicitud', [$startDate, $endDate]);
        }
      

        // Obtener las solicitudes filtradas
        $solicitudes = $solicitudesQuery->orderBy('fecha_solicitud', 'desc')->get();

        // Pasar los datos a la vista
        return view('Vacaciones.index', compact('saldo', 'solicitudes', 'empleados', 'empleadoId', 'startDate', 'endDate'));
    }

    public function crearSolicitud(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'fecha_solicitud' => 'required|date',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date',
            'dias_solicitados' => 'required|integer',
            'comentarios' => 'nullable|string',
        ]);



        // Crear la solicitud de vacaciones
        $solicitud = SolicitudVacaciones::create([
            'empleado_id' => auth()->user()->empleado->id,
            'fecha_solicitud' => now(),
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'dias_solicitados' => $request->dias_solicitados,
            'estado' => 'pendiente', // Estado inicial
            'comentarios' => $request->comentarios,
        ]);

        // Redirigir con un mensaje de éxito
        return redirect()->route('vacaciones.index')->with('success', 'Solicitud de vacaciones creada correctamente.');
    }


    public function eliminarSolicitud($id)
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

        // Permitir solo a administradores, gerente general o asistente de gerencia eliminar solicitudes
        if ($user->isAdmin() || $user->isGerenteGeneral() || $user->isAsistenteGerencial())  {
            // Buscar la solicitud y eliminarla
            $solicitud = SolicitudVacaciones::findOrFail($id);
            $solicitud->delete();

            // Redirigir con un mensaje de éxito
            return redirect()->route('vacaciones.index')->with('success', 'Solicitud de vacaciones eliminada correctamente.');
        } else {
            // Redirigir con un mensaje de error si el usuario no tiene permisos
            return redirect()->route('vacaciones.index')->with('error', 'No tienes permisos para realizar esta acción.');
        }
    }



    public function updateEstado(Request $request, $id)
    {
        $permiso = SolicitudVacaciones::findOrFail($id);
        $user = Auth::user();

        // Verificar si el usuario es un administrador y el Gerente General y Asistente de Gerencia
        if ($user->isAdmin() || ($user->empleado->es_supervisor) || $user->isGerenteGeneral() || $user->isAsistenteGerencial()) {
            $request->validate(['estado' => 'required|in:Pendiente,Aprobado,Rechazado']);

            // Guardar el usuario que aprobó si el estado es "Aprobado"
            if ($request->estado == 'Aprobado') {
                $permiso->update([
                    'estado' => $request->estado,
                    'aprobado_por' => $user->id, // Asumiendo que tienes una columna 'aprobado_por' en la tabla 'solicitud_vacaciones'
                ]);

                // Actualizar los días tomados y saldo de vacaciones
                // Actualizar los días tomados y saldo de vacaciones
                $vacacion = Vacacion::where('empleado_id', $permiso->empleado_id)->first();

                if ($vacacion) {
                    $vacacion->update([
                        'dias_tomados' => $vacacion->dias_tomados + $permiso->dias_solicitados,
                        'saldo_vacaciones' => $vacacion->saldo_vacaciones - $permiso->dias_solicitados,
                    ]);
                } else {
                    // Manejar el caso cuando no se encuentra el registro
                    return redirect()->back()->with('error', 'No se encontró el saldo de vacaciones para este empleado.');
                }
            } else {
                $permiso->update(['estado' => $request->estado]);
            }

            // Redirigir con un mensaje de éxito
            return redirect()->route('vacaciones.index')->with('success', 'Estado de la solicitud de vacaciones actualizado correctamente y se Actualizo el Saldo de Vacaciones.');
        } else {
            return redirect()->route('vacaciones.index')->with('error', 'No tienes permisos para realizar esta acción.');
        }
    }



    public function editarSolicitud(Request $request, $id)
    {
        // Verificar si el usuario es administrador
        if (Auth::user()->rol !== 'admin') {
            return redirect()->route('vacaciones.index')->with('error', 'No tienes permisos para realizar esta acción.');
        }

        // Validar los datos del formulario
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'dias_solicitados' => 'required|integer|min:1',
            'comentarios' => 'nullable|string',
        ]);

        // Buscar la solicitud y actualizarla
        $solicitud = SolicitudVacaciones::findOrFail($id);
        $solicitud->update([
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'dias_solicitados' => $request->dias_solicitados,
            'comentarios' => $request->comentarios,
        ]);

        // Redirigir con un mensaje de éxito
        return redirect()->route('vacaciones.index')->with('success', 'Solicitud de vacaciones actualizada correctamente.');
    }

    public function indexSaldo()
    {
        // Verificar si el usuario es administrador o el usuario con id = 3
        $user = Auth::user();
        
        if (!$user->isAdmin() && $user->isGerenteGeneral() && $user->isAsistenteGerencial()) {
            // Redirigir con un mensaje de error si el usuario no tiene permisos
            return redirect()->route('vacaciones.index')->with('error', 'No tienes permisos para realizar esta acción.');
        }

        // Obtener todos los registros de saldo de vacaciones
        $vacaciones = Vacacion::with('empleado')->get();

        // Obtener todos los empleados (si es necesario en la vista)
        $empleados = Empleados::all();

        // Pasar los datos a la vista
        return view('Vacaciones.indexSaldo', compact('vacaciones', 'empleados'));
    }

    // public function crearSaldo(Request $request)
    // {
    //     // Verificar si el usuario es administrador o el usuario con id = 3
    //     $user = Auth::user();
    //     if (!$user->isAdmin() && $user->empleado->id != 3) {
    //         return redirect()->route('vacaciones.index')->with('error', 'No tienes permisos para realizar esta acción.');
    //     }

    //     // Validar los datos del formulario
    //     $request->validate([
    //         'empleado_id' => 'required|exists:empleados,id',
    //         'fecha_ingreso' => 'required|date',
    //         'periodo' => 'required|string',
    //         'saldo_vacaciones' => 'required|integer|min:0',
    //         'dias_tomados' => 'required|integer|min:0',
    //     ]);

    //     // Crear el nuevo registro de saldo de vacaciones
    //     Vacacion::create([
    //         'empleado_id' => $request->empleado_id,
    //         'fecha_ingreso' => $request->fecha_ingreso,
    //         'periodo' => $request->periodo,
    //         'saldo_vacaciones' => $request->saldo_vacaciones,
    //         'dias_tomados' => $request->dias_tomados,
    //     ]);

    //     // Redirigir con un mensaje de éxito
    //     return redirect()->route('vacaciones.index.saldo')->with('success', 'Saldo de vacaciones creado correctamente.');
    // }


    // public function editarSaldo(Request $request, $id)
    // {
    //     // Verificar si el usuario es administrador o el usuario con id = 3
    //     $user = Auth::user();
    //     if (!$user->isAdmin() && $user->empleado->id != 3) {
    //         return redirect()->route('vacaciones.index')->with('error', 'No tienes permisos para realizar esta acción.');
    //     }

    //     // Validar los datos del formulario
    //     $request->validate([
    //         'dias_tomados' => 'required|integer|min:0',
    //         'saldo_vacaciones' => 'required|integer|min:0',
    //     ]);

    //     // Buscar el registro de saldo de vacaciones y actualizarlo
    //     $vacacion = Vacacion::findOrFail($id);
    //     $vacacion->update([
    //         'dias_tomados' => $request->dias_tomados,
    //         'saldo_vacaciones' => $request->saldo_vacaciones,
    //     ]);

    //     // Redirigir con un mensaje de éxito
    //     return redirect()->route('vacaciones.index.saldo')->with('success', 'Saldo de vacaciones actualizado correctamente.');
    // }

    // Crear un nuevo saldo de vacaciones
    public function crearSaldo(Request $request)
    {
        // Verificar si el usuario es administrador o el usuario con id = 3
        $user = Auth::user();
        if (!$user->isAdmin() && $user->isGerenteGeneral() && $user->isAsistenteGerencial()) {
            // Redirigir con un mensaje de error si el usuario no tiene permisos
            return redirect()->route('vacaciones.index')->with('error', 'No tienes permisos para realizar esta acción.');
        }

        // Validar los datos del formulario
        $request->validate([
            'empleado_id' => 'required|exists:empleados,id',
            'fecha_ingreso' => 'required|date',
            'periodo' => 'required|string',
            'desde' => 'nullable|date',
            'hasta' => 'nullable|date',
            'dias_tomados' => 'required|integer|min:0',
            'saldo_vacaciones' => 'required|integer|min:0',
        ]);

        // Crear el nuevo registro de saldo de vacaciones
        Vacacion::create([
            'empleado_id' => $request->empleado_id,
            'fecha_ingreso' => $request->fecha_ingreso,
            'periodo' => $request->periodo,
            'desde' => $request->desde,
            'hasta' => $request->hasta,
            'dias_tomados' => $request->dias_tomados,
            'saldo_vacaciones' => $request->saldo_vacaciones,
        ]);

        // Redirigir con un mensaje de éxito
        return redirect()->route('vacaciones.indexSaldo')->with('success', 'Saldo de vacaciones creado correctamente.');
    }

    // Editar un saldo de vacaciones existente
    public function editarSaldo(Request $request, $id)
    {
        // Verificar si el usuario es administrador o el usuario con id = 3
        $user = Auth::user();
        if (!$user->isAdmin() && $user->isGerenteGeneral() && $user->isAsistenteGerencial()) {
            // Redirigir con un mensaje de error si el usuario no tiene permisos
            return redirect()->route('vacaciones.index')->with('error', 'No tienes permisos para realizar esta acción.');
        }

        // Validar los datos del formulario
        $request->validate([
            'empleado_id' => 'required|exists:empleados,id',
            'fecha_ingreso' => 'required|date',
            'periodo' => 'required|string',
            'desde' => 'nullable|date',
            'hasta' => 'nullable|date',
            'dias_tomados' => 'required|integer|min:0',
            'saldo_vacaciones' => 'required|integer|min:0',
        ]);

        // Buscar el registro de saldo de vacaciones y actualizarlo
        $vacacion = Vacacion::findOrFail($id);
        $vacacion->update([
            'empleado_id' => $request->empleado_id,
            'fecha_ingreso' => $request->fecha_ingreso,
            'periodo' => $request->periodo,
            'desde' => $request->desde,
            'hasta' => $request->hasta,
            'dias_tomados' => $request->dias_tomados,
            'saldo_vacaciones' => $request->saldo_vacaciones,
        ]);

        // Redirigir con un mensaje de éxito
        return redirect()->route('vacaciones.indexSaldo')->with('success', 'Saldo de vacaciones actualizado correctamente.');
    }

    // Eliminar un saldo de vacaciones
    public function eliminarSaldo($id)
    {
        // Verificar si el usuario es administrador o el usuario con id = 3
        $user = Auth::user();
        if (!$user->isAdmin() && $user->isGerenteGeneral() && $user->isAsistenteGerencial()) {
            // Redirigir con un mensaje de error si el usuario no tiene permisos
            return redirect()->route('vacaciones.index')->with('error', 'No tienes permisos para realizar esta acción.');
        }

        // Buscar el registro de saldo de vacaciones y eliminarlo
        $vacacion = Vacacion::findOrFail($id);
        $vacacion->delete();

        // Redirigir con un mensaje de éxito
        return redirect()->route('vacaciones.indexSaldo')->with('success', 'Saldo de vacaciones eliminado correctamente.');
    }
}
