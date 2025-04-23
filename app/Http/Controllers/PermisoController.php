<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permiso;
use App\Models\Empleados;
use App\Models\Vacacion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;


class PermisoController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $empleadoId = $request->input('empleado_id');
        $fechaSeleccionada = $request->input('fecha'); // No hay valor por defecto

        $permisosQuery = Permiso::with(['empleado', 'aprobadoPor']);

        // Filtros según el rol del usuario
        if ($user->isAdmin()) {
            $permisosQuery->when($empleadoId, function ($query) use ($empleadoId) {
                return $query->where('empleado_id', $empleadoId);
            });
            $empleados = Empleados::all();
        } elseif ($user->isGerenteGeneral() || $user->isAsistenteGerencial()) {
            $permisosQuery->when($empleadoId, function ($query) use ($empleadoId) {
                return $query->where('empleado_id', $empleadoId);
            });
            $empleados = Empleados::all();
        } elseif ($user->isSupervisor()) {
            $permisosQuery->where(function ($query) use ($user) {
                $query->whereHas('empleado', function ($subQuery) use ($user) {
                    $subQuery->where('supervisor_id', $user->empleado->id);
                })->orWhere('empleado_id', $user->empleado->id);
            });
            if ($empleadoId) {
                $permisosQuery->where('empleado_id', $empleadoId);
            }
            $empleados = Empleados::where('supervisor_id', $user->empleado->id)->get();
        } elseif ($user->isEmpleado()) {
            $permisosQuery->where('empleado_id', $user->empleado->id);
            $empleados = collect();
        } else {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta página');
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
            $permisosQuery->whereBetween('fecha_salida', [$startDate, $endDate]);
        }

        // Ordenar por fecha de salida descendente (últimos primero)
        $permisos = $permisosQuery->orderBy('fecha_salida', 'desc')->get();

        // Calcular horas no justificadas
        $horasNoJustificadasPorEmpleado = [];
        foreach ($permisos as $permiso) {
            if (!$permiso->justificado && $permiso->estado === 'Aprobado') {
                $horaSalida = \Carbon\Carbon::createFromFormat('H:i:s', $permiso->hora_salida);
                $horaRegreso = \Carbon\Carbon::createFromFormat('H:i:s', $permiso->hora_regreso);
                $duracion = $horaSalida->diffInMinutes($horaRegreso) / 60;

                if (!isset($horasNoJustificadasPorEmpleado[$permiso->empleado_id])) {
                    $horasNoJustificadasPorEmpleado[$permiso->empleado_id] = 0;
                }
                $horasNoJustificadasPorEmpleado[$permiso->empleado_id] += $duracion;
            }
        }

        return view('Permisos.index', compact('permisos', 'empleados', 'horasNoJustificadasPorEmpleado'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fecha_salida' => 'required|date',
            'hora_salida' => 'required|date_format:H:i',
            'hora_regreso' => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) use ($request) {
                    $horaSalida = \Carbon\Carbon::createFromFormat('H:i', $request->hora_salida);
                    $horaRegreso = \Carbon\Carbon::createFromFormat('H:i', $value);
                    if ($horaRegreso <= $horaSalida) {
                        $fail('La hora de regreso debe ser mayor que la hora de salida.');
                    }
                },
            ],
            'tipo_permiso' => 'required|in:Personal,Enfermedad,Estudio,Defuncion,Maternidad/Paternidad,Otro',
            'motivo' => 'nullable|required_if:tipo_permiso,Otro',
            'anexos' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'justificado' => 'nullable|boolean',
        ]);

        // Calcular la duración
        $horaSalida = \Carbon\Carbon::createFromFormat('H:i', $request->hora_salida);
        $horaRegreso = \Carbon\Carbon::createFromFormat('H:i', $request->hora_regreso);
        $duracion = $horaSalida->diff($horaRegreso)->format('%H:%I:%S');

        // Guardar el permiso
        $permiso = Permiso::create([
            'empleado_id' => auth()->user()->empleado->id,
            'fecha_solicitud' => now(),
            'fecha_salida' => $request->fecha_salida,
            'hora_salida' => $request->hora_salida,
            'hora_regreso' => $request->hora_regreso,
            'duracion' => $duracion,
            'tipo_permiso' => $request->tipo_permiso,
            'motivo' => $request->tipo_permiso === 'Otro' ? $request->motivo : null,
            'estado' => 'Pendiente',
            'anexos' => $request->hasFile('anexos') ? $request->file('anexos')->store('anexos', 'public') : null,
            'justificado' => $request->justificado ?? false,
        ]);




        return redirect()->route('permisos.index')->with('success', 'Permiso registrado con éxito.');
    }

    public function update(Request $request, $id)
    {
        try {
            $permiso = Permiso::findOrFail($id);
            $user = Auth::user();

            // Validación para actualizar el motivo (solo admin y los cargos que tengan acceso)
            if ($user->isAdmin() || $user->isGerenteGeneral() || $user->isAsistenteGerencial()) {
                $request->validate(['motivo' => 'required|string']);
                $permiso->update(['motivo' => $request->motivo]);
            }

            // Validación para actualizar el estado (supervisores y supervisor superior)
            if ($user->isAdmin() || $user->isGerenteGeneral() || $user->isAsistenteGeneral() || ($user->empleado && ($user->empleado->es_supervisor))) {
                $request->validate([
                    'estado' => 'required|string|in:Pendiente,Aprobado,Rechazado,Procesado',
                ]);
                $permiso->update(['estado' => $request->estado]);
                return redirect()->route('Permisos.index')->with('success', 'Estado actualizado correctamente');
            }


            // Validación para actualizar anexos (solo el dueño del permiso)
            if ($user->empleado && $user->empleado->id === $permiso->empleado_id) {
                if ($request->hasFile('anexos')) {
                    $request->validate(['anexos' => 'file|mimes:pdf,jpg,png']);
                    if ($permiso->anexos) {
                        Storage::disk('public')->delete($permiso->anexos);
                    }
                    $permiso->update(['anexos' => $request->file('anexos')->store('anexos', 'public')]);
                }
            }

            return redirect()->route('permisos.index')->with('success', 'Permiso actualizado correctamente');
        } catch (\Exception $e) {

            return redirect()->route('permisos.index')->with('error', 'Error al actualizar el permiso: ' . $e->getMessage());
        }
    }


    public function updateHoraRegreso(Request $request, Permiso $permiso)
    {
        $request->validate([
            'hora_regreso' => 'required|date_format:H:i',
        ]);

        // Calcular la duración
        $horaSalida = \Carbon\Carbon::createFromFormat('H:i:s', $permiso->hora_salida);
        $horaRegreso = \Carbon\Carbon::createFromFormat('H:i', $request->hora_regreso);
        $duracion = $horaSalida->diff($horaRegreso)->format('%H:%I:%S');

        // Actualizar la hora de regreso y la duración
        $permiso->update([
            'hora_regreso' => $request->hora_regreso,
            'duracion' => $duracion,
        ]);

        // Obtener todos los parámetros de filtro de la solicitud
        $filtros = $request->only(['empleado_id', 'start_date', 'end_date', 'filtro', 'semana', 'mes']);

        return redirect()->route('permisos.index', $filtros)->with('success', 'Hora de regreso actualizada con éxito.');
    }

    public function updateAnexo(Request $request, $id)
    {
        $request->validate([
            'anexos' => 'required|file|mimes:pdf,jpg,png|max:2048'
        ]);

        $permiso = Permiso::findOrFail($id);



        // Guardar el nuevo archivo
        if ($request->hasFile('anexos')) {
            $anexoPath = $request->file('anexos')->store('anexos', 'public');
            $permiso->anexos = $anexoPath;
            $permiso->save();
        }

        // Obtener todos los parámetros de filtro de la solicitud
        $filtros = $request->only(['empleado_id', 'start_date', 'end_date', 'filtro', 'semana', 'mes']);
        // Redirigir manteniendo los filtros
        return redirect()->route('permisos.index', $filtros)->with('success', 'Anexo actualizado correctamente.');
    }


    public function destroy($id)
    {
        $permiso = Permiso::findOrFail($id);

        if (!Auth::user()->isAdmin()) {
            abort(403, 'No autorizado');
        }

        if ($permiso->anexos) {
            Storage::disk('public')->delete($permiso->anexos);
        }

        $permiso->delete();
        return redirect()->route('permisos.index')->with('success', 'Permiso eliminado correctamente');
    }

    public function updateEstado(Request $request, $id)
    {
        $permiso = Permiso::findOrFail($id);
        $user = Auth::user();

        // Verificar si el usuario es admin, supervisor, o el usuario específico con id = 3
        if ($user->isAdmin() || ($user->empleado && $user->empleado->es_supervisor) || $user->isGerenteGeneral() || $user->isAsistenteGerencial()) {
            // Validar el estado del permiso
            $request->validate(['estado' => 'required|in:Pendiente,Aprobado,Rechazado']);

            // Actualizar el estado del permiso
            $permiso->estado = $request->estado;

            // Si el estado es "Aprobado", guardar quién lo aprobó
            if ($request->estado === 'Aprobado') {
                $permiso->aprobado_por = $user->id;
            } else {
                // Si no es "Aprobado", limpiar el campo aprobado_por
                $permiso->aprobado_por = null;
            }

            $permiso->save();

            // Obtener todos los parámetros de filtro de la solicitud
            $filtros = $request->only(['empleado_id', 'start_date', 'end_date', 'filtro', 'semana', 'mes']);

            return redirect()->route('permisos.index' , $filtros)
                ->with('success', 'Estado del permiso actualizado correctamente.');
        } else {
            // Si no cumple ninguna de las condiciones, denegar acceso
            abort(403, 'No autorizado');
        }
    }


    // public function disminuirSaldoVacaciones($permiso)
    // {
    //     $empleado = $permiso->empleado;
    //     $vacacion = Vacacion::where('empleado_id', $empleado->id)->first();

    //     if (!$vacacion) {
    //         return ['error' => 'No se ha encontrado un registro de vacaciones asociado con este empleado. Por favor, verifique que la información esté actualizada.'];
    //     }

    //     //Inicializar las horas justificadas 
    //     $horasJustificadas = 0;

    //     // Obtener todos los permisos no justificados (justificado == false) del empleado
    //     // Excluir los permisos que ya han sido procesados (procesado == false)
    //     $permisosNoJustificados = Permiso::where('empleado_id', $empleado->id)
    //         ->where('justificado', false)  // Solo permisos no justificados
    //         ->where('estado', 'Aprobado')  // Solo permisos aprobados
    //         ->where('procesado', false)    // Excluir los permisos que ya han sido procesados
    //         ->get();

    //     $horasTotalesNoJustificadas = 0;

    //     // Sumar las horas de los permisos no justificados
    //     foreach ($permisosNoJustificados as $perm) {
    //         $horaSalida = \Carbon\Carbon::createFromFormat('H:i:s', $perm->hora_salida);
    //         $horaRegreso = \Carbon\Carbon::createFromFormat('H:i:s', $perm->hora_regreso);
    //         $duracionTotal = $horaSalida->diffInMinutes($horaRegreso) / 60;  // Convertir minutos a horas decimales

    //         $horasTotalesNoJustificadas += $duracionTotal;
    //     }

    //     // Si las horas no justificadas superan las 8 horas, descontar un día de vacaciones


    //     if ($horasTotalesNoJustificadas >= 8) {
    //         if ($vacacion->saldo_vacaciones > 0) {
    //             $vacacion->saldo_vacaciones -= 1;  // Descontar un día de vacaciones
    //             $vacacion->dias_tomados += 1;     // Aumentar los días tomados
    //             $vacacion->save();

    //             return ['success' => 'Se ha descontado 1 día de vacaciones debido a permisos no justificados. El saldo de vacaciones ha sido actualizado del Empleado.'];
    //         } else {
    //             return ['error' => 'No hay suficiente saldo de vacaciones para descontar el día. Por favor, verifique el saldo disponible o comuníquese con RRHH.'];
    //         }
    //     }

    //     //Verificar si el empleado ha justificado las horas no trabajadas
    //     if ($empleado->justificoHoras) {
    //         $horasJustificadas = $empleado->justificoHoras;

    //         //Si el empleado justifica las horas no justificadas, añadir un día de vacaciones
    //         if ($horasJustificadas > 0 && $horasTotalesNoJustificadas >= 8) {
    //             // Si el empleado justifica algunas horas, añade el día de vuelta
    //             $vacacion->saldo_vacaciones += 1;  // Añadir un día de vacaciones
    //             $vacacion->dias_tomados -= 1;     // Reducir los días tomados
    //             $vacacion->save();

    //             return ['success' => 'Se ha añadido 1 día de vacaciones al saldo, ya que el empleado justificó las horas no justificadas previamente.'];
    //         }
    //     }

    //     return ['error' => 'No se ha descontado ningún día de vacaciones del Empleado, ya que no se ha superado el límite de horas no justificadas.'];
    // }





    public function toggleJustificacion(Request $request, Permiso $permiso)
    {
        // Obtener el empleado y su registro de vacaciones
        $empleado = $permiso->empleado;
        $vacacion = Vacacion::where('empleado_id', $empleado->id)->first();

        // Verificar si el permiso está aprobado y no justificado
        if ($permiso->estado === 'Aprobado' && !$permiso->justificado) {
            // Calcular la duración del permiso en horas
            $horaSalida = \Carbon\Carbon::createFromFormat('H:i:s', $permiso->hora_salida);
            $horaRegreso = \Carbon\Carbon::createFromFormat('H:i:s', $permiso->hora_regreso);
            $duracionPermiso = $horaSalida->diffInMinutes($horaRegreso) / 60;  // Duración en horas

            // Si el permiso excede las 8 horas y no ha sido justificado
            if ($duracionPermiso > 8) {
                // Verificar si ya se descontó un día de vacaciones
                if ($vacacion->dias_tomados > 0 && !$permiso->procesado) {
                    // Descontar un día de vacaciones si no se ha procesado aún
                    $vacacion->saldo_vacaciones -= 1;
                    $vacacion->dias_tomados += 1; // Aumentar los días tomados
                    $vacacion->save();

                    // Marcar el permiso como procesado
                    $permiso->procesado = true;
                    $permiso->save();
                }
            }
        }

        // Si el permiso es justificado, devolver un día de vacaciones si fue descontado previamente
        if ($request->justificado) {
            // Si el permiso fue procesado y el día fue descontado, restaurarlo
            if ($permiso->procesado && $vacacion->saldo_vacaciones > 0) {
                // Añadir el día de vuelta si se había descontado
                $vacacion->saldo_vacaciones += 1;  // Devolver un día de vacaciones
                $vacacion->dias_tomados -= 1;      // Reducir los días tomados
                $vacacion->save();
            }

            // Actualizar el permiso como justificado
            $permiso->justificado = true;
            $permiso->save();
        }

        // Si el permiso no es justificado, no lo procesamos
        if (!$request->justificado) {
            $permiso->justificado = false;
            $permiso->save();
        }

        return response()->json(['success' => true]);
    }


    public function indexHoras(Request $request)
    {
        $user = Auth::user();
        $empleadoId = $request->input('empleado_id');
        $filtro = $request->input('filtro', 'todos'); // Cambiado valor por defecto a 'todos'
        $semanaSeleccionada = $request->input('semana', 0);
        $mesSeleccionado = $request->input('mes', now()->format('Y-m'));

        $permisosQuery = Permiso::with('empleado')
            ->where('estado', 'Aprobado')
            ->where('justificado', false)
            ->where('procesado', false);

        // Lógica para obtener los permisos según el rol del usuario
        if ($user->isAdmin() || $user->isAsistenteGerencial() || $user->isGerenteGeneral()) {
            $permisosQuery = $permisosQuery->when($empleadoId, function ($query) use ($empleadoId) {
                return $query->where('empleado_id', $empleadoId);
            });
            $empleados = Empleados::all();
        } elseif ($user->isSupervisor()) {
            $permisosQuery = $permisosQuery->where(function ($query) use ($user, $empleadoId) {
                $query->whereHas('empleado', function ($subQuery) use ($user) {
                    $subQuery->where('supervisor_id', $user->empleado->id);
                })->orWhere('empleado_id', $user->empleado->id);
            });

            if ($empleadoId) {
                $permisosQuery->where('empleado_id', $empleadoId);
            }

            $empleados = Empleados::where('supervisor_id', $user->empleado->id)->get();
        } elseif ($user->isEmpleado()) {
            $permisosQuery = $permisosQuery->where('empleado_id', $user->empleado->id);
            $empleados = collect();
        } else {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta página');
        }

        // Filtrar permisos por fecha_salida (fecha del permiso) en lugar de created_at
        if ($filtro === 'semana') {
            $inicioSemana = now()->startOfWeek()->subWeeks($semanaSeleccionada);
            $finSemana = now()->endOfWeek()->subWeeks($semanaSeleccionada);

            $permisosQuery->whereBetween('fecha_salida', [$inicioSemana, $finSemana]);
        } elseif ($filtro === 'mes') {
            $inicioMes = Carbon::parse($mesSeleccionado)->startOfMonth();
            $finMes = Carbon::parse($mesSeleccionado)->endOfMonth();

            $permisosQuery->whereBetween('fecha_salida', [$inicioMes, $finMes]);
        }
        // Si $filtro es 'todos', no aplicamos filtro de fecha

        // Obtener los permisos y pasarlos a la vista
        $permisos = $permisosQuery->orderBy('fecha_salida', 'desc')->get();

        // Calcular horas no justificadas acumuladas por empleado, incluyendo sobrante de horas
        $horasNoJustificadasPorEmpleado = [];
        $sobrantePorEmpleado = [];

        foreach ($permisos as $permiso) {
            $horaSalida = \Carbon\Carbon::createFromFormat('H:i:s', $permiso->hora_salida);
            $horaRegreso = \Carbon\Carbon::createFromFormat('H:i:s', $permiso->hora_regreso);
            $duracion = $horaSalida->diffInMinutes($horaRegreso) / 60; // Duración en horas

            if (!isset($horasNoJustificadasPorEmpleado[$permiso->empleado_id])) {
                $horasNoJustificadasPorEmpleado[$permiso->empleado_id] = 0;
            }

            $horasNoJustificadasPorEmpleado[$permiso->empleado_id] += $duracion;
        }

        // Obtener las horas sobrantes de cada empleado
        foreach ($empleados as $empleado) {
            $sobrantePorEmpleado[$empleado->id] = $empleado->sobrante_horas ?? 0;
            if (isset($horasNoJustificadasPorEmpleado[$empleado->id])) {
                $horasNoJustificadasPorEmpleado[$empleado->id] += $sobrantePorEmpleado[$empleado->id];
            }
        }

        return view('Permisos.indexHoras', compact(
            'permisos',
            'empleados',
            'horasNoJustificadasPorEmpleado',
            'empleadoId',
            'sobrantePorEmpleado',
            'filtro',
            'semanaSeleccionada',
            'mesSeleccionado'
        ));
    }


    public function calcularHoras(Request $request)
    {
        $empleadoId = $request->input('empleado_id');

        // Obtener permisos no justificados y no procesados
        $permisosQuery = Permiso::with('empleado')
            ->where('estado', 'Aprobado')
            ->where('justificado', false)
            ->where('procesado', false)
            ->where('empleado_id', $empleadoId)
            ->get();

        $empleado = Empleados::find($empleadoId);
        $sobrante = $empleado->sobrante_horas ?? 0;

        // Calcular horas no justificadas totales
        $horasNoJustificadas = $sobrante;
        foreach ($permisosQuery as $permiso) {
            $horaSalida = \Carbon\Carbon::createFromFormat('H:i:s', $permiso->hora_salida);
            $horaRegreso = \Carbon\Carbon::createFromFormat('H:i:s', $permiso->hora_regreso);
            $duracion = $horaSalida->diffInMinutes($horaRegreso) / 60;
            $horasNoJustificadas += $duracion;
        }

        // Calcular días completos y nuevo sobrante
        $diasADescontar = intdiv($horasNoJustificadas, 8);
        $nuevoSobrante = $horasNoJustificadas - ($diasADescontar * 8);
        // Actualizar sobrante
        $empleado->sobrante_horas = $nuevoSobrante;
        $empleado->save();

        if ($diasADescontar > 0) {
            $vacacion = Vacacion::where('empleado_id', $empleadoId)->first();

            if ($vacacion && $vacacion->saldo_vacaciones >= $diasADescontar) {
                $vacacion->saldo_vacaciones -= $diasADescontar;
                $vacacion->dias_tomados += $diasADescontar;
                $vacacion->save();

                // Marcar permisos como procesados
                Permiso::whereIn('id', $permisosQuery->pluck('id'))->update(['procesado' => true]);

                return redirect()->route('Permisos.indexHoras', ['empleado_id' => $empleadoId])
                    ->with('success', "Se han descontado $diasADescontar días de vacaciones.");
            } else {
                return redirect()->route('Permisos.indexHoras', ['empleado_id' => $empleadoId])
                    ->with('error', 'No hay suficiente saldo de vacaciones.');
            }
        }

        return redirect()->route('Permisos.indexHoras', ['empleado_id' => $empleadoId])
            ->with('success', "Horas no justificadas acumuladas: $nuevoSobrante horas.");
    }




    public function horasNoJustificadas(Request $request)
    {
        // Obtener todos los permisos
        $permisos = Permiso::all();
        $horasNoJustificadasPorEmpleado = [];

        foreach ($permisos as $permiso) {
            if (!$permiso->justificado) {
                // Calcular las horas no justificadas (puedes ajustar esta lógica según cómo calculas las horas)
                $duracion = $this->calcularHorasNoJustificadas($permiso);
                $horasNoJustificadasPorEmpleado[$permiso->empleado_id] = isset($horasNoJustificadasPorEmpleado[$permiso->empleado_id])
                    ? $horasNoJustificadasPorEmpleado[$permiso->empleado_id] + $duracion
                    : $duracion;
            }
        }

        // Pasar los datos a la vista
        return view('permisos.indexHoras', [
            'horasNoJustificadasPorEmpleado' => $horasNoJustificadasPorEmpleado
        ]);
    }
}
