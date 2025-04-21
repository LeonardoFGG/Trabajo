<?php

/*****************************************************
 * Nombre del Proyecto: ERP 
 * Modulo: Actividades
 * Version: 1.0
 * Desarrollado por: Karol Macas
 * Fecha de Inicio: 
 * Ultima Modificación: 
 ****************************************************/

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Actividades;
use App\Models\Empleados;
use App\Models\Departamento;
use App\Models\Cliente;
use App\Models\Cargos;
use App\Models\Supervisor;
use App\Models\Producto;
use Maatwebsite\Excel\Facades\Excel; // Agrega esta línea al inicio
use App\Exports\ActividadesExport; // Ensure this class exists in the App\Exports namespace

// Ensure the ActividadesExport class exists in the App\Exports namespace
use Barryvdh\DomPDF\Facade as PDF;  // Agrega esta línea con las otras importaciones

use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;


class ActividadesController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $empleadoId = $request->input('empleado_id');

        // Cargar relaciones necesarias
        $actividadesQuery = Actividades::with([
            'empleado',
            'cliente',
            'departamento',
            'producto' => function ($query) {
                $query->select('id', 'nombre');
            }
        ]);



        // Aplicar filtros según el tipo de usuario
        if ($user->isAdmin() || $user->isAsistenteGerencial() || $user->isGerenteGeneral()) {
            $actividadesQuery->when($empleadoId, function ($query) use ($empleadoId) {
                return $query->where('empleado_id', $empleadoId);
            });
            $empleados = Empleados::all(['id', 'nombre1', 'apellido1']);
        } elseif ($user->isSupervisor()) {
            // Obtener ID del supervisor
            $supervisorId = $user->empleado->id;

            // Obtener empleados a cargo (incluyendo al supervisor)
            $empleados = Empleados::where('supervisor_id', $supervisorId)
                ->orWhere('id', $supervisorId)
                ->get(['id', 'nombre1', 'apellido1']);

            // Filtrar actividades
            $empleados = Empleados::where('supervisor_id', $supervisorId)
                ->orWhere('id', $supervisorId)
                ->get(['id', 'nombre1', 'apellido1']);

            $actividadesQuery->where(function ($query) use ($supervisorId, $empleadoId, $empleados) {
                // Si se seleccionó un empleado específico
                if ($empleadoId) {
                    // Verificar que el empleado seleccionado está bajo su supervisión
                    $empleadoValido = $empleados->contains('id', $empleadoId);
                    if ($empleadoValido) {
                        $query->where('empleado_id', $empleadoId);
                    }
                } else {
                    // Mostrar actividades de todos sus empleados a cargo
                    $query->whereHas('empleado', function ($q) use ($supervisorId) {
                        $q->where('supervisor_id', $supervisorId)
                            ->orWhere('id', $supervisorId);
                    });
                }
            });
        } elseif ($user->isEmpleado()) {
            $actividadesQuery->where('empleado_id', $user->empleado->id);
            $empleados = collect([$user->empleado]);
        } else {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta página');
        }

        // Resto de filtros
        if ($request->has('servicio_hora')) {
            $actividadesQuery->whereHas('producto', function ($q) {
                $q->where('nombre', 'servicio por hora', 'servicio por hora (anticipada)');
            });
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
            $actividadesQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Obtener resultados
        $actividades = $actividadesQuery
            ->select('actividades.*')
            ->orderBy('created_at', 'desc')
            ->paginate(100);

        // Contar estados
        $statusCounts = $actividades->groupBy('estado')->map->count();

        $clientes = Cliente::all();
        $departamentos = Departamento::all();
        $cargos = Cargos::all();

        //verificacion de avance de actividad, seleccionar tiempo limite para actividades olvidadas
        $now = Carbon::now()->setTimezone('America/Guayaquil');
        $limiteMinutos = 45; // Para pruebas; cambiar a 480 (8h) luego

        $actividadesExcedidas = \App\Models\Actividades::where('estado', 'EN CURSO')
            ->whereNotNull('tiempo_inicio')
            ->get();

        foreach ($actividadesExcedidas as $actividad) {
            $inicio = Carbon::parse($actividad->tiempo_inicio)->setTimezone('America/Guayaquil');
            $transcurrido = $now->diffInMinutes($inicio);

            if (($actividad->tiempo_acumulado_minutos + $transcurrido) >= $limiteMinutos) {
                $totalMin = $actividad->tiempo_acumulado_minutos + $transcurrido;

                $actividad->estado = 'FINALIZADO';
                $actividad->tiempo_acumulado_minutos += $transcurrido;
                $actividad->tiempo_inicio = null; // Cortar el conteo
                $actividad->fecha_fin = now();
                // Calcular y guardar el tiempo real
                $horas = floor($actividad->tiempo_acumulado_minutos / 60);
                $minutos = $actividad->tiempo_acumulado_minutos % 60;

                $actividad->tiempo_real_horas = $horas;
                $actividad->tiempo_real_minutos = $minutos;

                $actividad->save();
                // mostrar notificacion de actividad finalizada automatticente
                if (Auth::user()->empleado && Auth::user()->empleado->id === $actividad->empleado_id) {
                    session()->push('actividades_finalizadas_auto', 'La actividad "' . $actividad->descripcion . '" fue finalizada automáticamente por superar el tiempo máximo permitido.');
                }
            }
        }


        return view('Actividades.indexActividades', [
            'actividades' => $actividades,
            'empleados' => $empleados,
            'clientes' => $clientes,
            'departamentos' => $departamentos,
            'cargos' => $cargos,
            'enCursoCount' => $statusCounts->get('EN CURSO', 0),
            'pendienteCount' => $statusCounts->get('PENDIENTE', 0),
            'finalizadoCount' => $statusCounts->get('FINALIZADO', 0),
            'filtro' => $request->input('filtro', 'fecha'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'empleado_id' => $request->input('empleado_id')
        ]);
    }


    public function markAsRead($id)
    {
        $empleado = auth()->user(); // Obtener el empleado logueado

        // Buscar la notificación y marcarla como leída
        $empleado->user->notifications()->find($id)->markAsRead();

        return redirect()->back();
    }


    public function create()
    {
        $user = Auth::user();
        $empleados = Empleados::all();
        $departamentos = Departamento::all();
        $clientes = Cliente::all();
        $cargos = Cargos::all();
        $productos = Producto::all();


        $departamento = null;
        $cargo = null;

        // Si es un administrador, pasar los datos de departamento y cargo
        if (Auth::user()->isAdmin() || Auth::user()->id == 3 || Auth::user()->id == 24) {
            // Aquí debes cargar el departamento, cargo y supervisor correspondientes
            $departamento = Departamento::find(1); // Asume que el administrador debe seleccionar un departamento
            $cargo = Cargos::find(1); // Asume que el administrador debe seleccionar un cargo
            $supervisor = Supervisor::find(1); // Ejemplo de supervisor
        }



        return view('Actividades.createActividades', compact('empleados', 'departamentos', 'clientes', 'cargos', 'productos'), request()->all());
    }

    public function getProductosByCliente($clienteId)
    {
        // Buscar el cliente
        $cliente = Cliente::find($clienteId);

        if ($cliente) {
            // Obtener los productos asociados al cliente
            $productos = $cliente->productos;
            return response()->json($productos);
        }

        // Si no se encuentra el cliente, devolver un array vacío
        return response()->json([]);
    }


    public function store(Request $request)
    {
        $user = Auth::user();

        // Validación de los demás campos de la actividad
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'producto_id' => 'required|exists:productos,id',
            'descripcion' => 'required|string|max:255',
            'codigo_osticket' => 'nullable|url',
            'semanal_diaria' => 'required|string|in:SEMANAL,DIARIO',
            'fecha_inicio' => 'required|date',
            'avance' => 'required|numeric|min:0|max:100',
            'observaciones' => 'nullable|string|max:255',
            'estado' => 'required|string|in:PENDIENTE,FINALIZADO',
            'tiempo_estimado' => 'required|integer',
            'repetitivo' => 'required|boolean',
            'prioridad' => 'required|string|in:ALTA,MEDIA,BAJA',
            'departamento_id' => 'required|exists:departamentos,id',
            'cargo_id' => 'required|exists:cargos,id',
            'error' => 'required|string|in:ESTRUCTURA,CLIENTE,SOFTWARE,MEJORA ERROR,DESARROLLO,OTRO',

        ], [
            'cliente_id.required' => 'Debe seleccionar un cliente.',
            'cliente_id.exists' => 'El cliente seleccionado no es válido.',
            'producto_id.required' => 'Debe seleccionar un producto.',
            'producto_id.exists' => 'El producto seleccionado no es válido.',

        ]);


        // Crea la actividad
        $actividad = new Actividades();
        $actividad->cliente_id = $request->input('cliente_id');
        $actividad->empleado_id = $request->input('empleado_id');
        $actividad->producto_id = $request->input('producto_id');
        $actividad->descripcion = $request->input('descripcion');
        $actividad->codigo_osticket = $request->input('codigo_osticket');
        $actividad->semanal_diaria = $request->input('semanal_diaria');
        $actividad->fecha_inicio = now(); // Esto guardará la fecha y hora actuales
        $actividad->avance = 0;
        $actividad->observaciones = $request->input('observaciones');
        $actividad->estado = 'PENDIENTE';
        $actividad->tiempo_estimado = $request->input('tiempo_estimado');
        $actividad->repetitivo = $request->input('repetitivo');
        $actividad->prioridad = $request->input('prioridad');
        $actividad->departamento_id = $request->input('departamento_id');
        $actividad->cargo_id = $request->input('cargo_id');
        $actividad->error = $request->input('error');

        $actividad->save();


        return redirect()->route('actividades.indexActividades')->with('success', 'Actividad creada con éxito.', request()->all());
    }

    public function edit($id)
    {
        $actividades = Actividades::findOrFail($id);
        $empleados = Empleados::all();
        $departamentos = Departamento::all();
        $clientes = Cliente::all();
        $cargos = Cargos::all();

        return view('Actividades.editActividades', compact('actividades', 'empleados', 'departamentos', 'clientes', 'cargos'), request()->all());
    }

    public function update(Request $request, $id)
    {

        $actividad = Actividades::findOrFail($id);
        // actualizar actividad
        $actividad->update($request->all());
        // Actualiza la descripcion
        if ($request->has('descripcion')) {
            $actividad->update($request->only('descripcion'));
            return redirect()->back()->with('success', 'Descripción actualizada correctamente.');
        }
        // Actualiza la tipo de error
        if ($request->has('error')) {
            $actividad->update($request->only('error'));
            return redirect()->back()->with('success', 'Tipo de error actualizado correctamente.');
        }
        $validated = $request->validate([
            'cliente_id' => 'required|string|max:255',
            'producto_id' => 'required|exists:productos,id',
            'empleado_id' => 'required|exists:empleados,id',
            'descripcion' => 'required|string|max:255',
            'codigo_osticket' => 'nullable|url',
            'semanal_diaria' => 'required|string|in:SEMANAL,DIARIO',
            'fecha_inicio' => 'required|date',
            'avance' => 'required|numeric|min:0|max:100',
            'observaciones' => 'nullable|string|max:255',
            'estado' => 'required|string|in:EN CURSO,FINALIZADO,PENDIENTE',
            'tiempo_estimado' => 'required|integer',
            'tiempo_real_horas' => 'nullable|integer',
            'tiempo_real_minutos' => 'nullable|integer',
            'tiempo_acumulado_minutos' => 'nullable|integer',
            'fecha_fin' => 'nullable|date',
            'repetitivo' => 'required|boolean',
            'prioridad' => 'required|string|in:ALTA,MEDIA,BAJA',
            'departamento_id' => 'required|exists:departamentos,id',
            'cargo_id' => 'required|exists:cargos,id',
            'error' => 'required|string|in:ESTRUCTURA,CLIENTE,SOFTWARE,MEJORA ERROR,DESARROLLO,OTRO',

        ]);

        $actividades = Actividades::findOrFail($id);
        $actividades->fill($validated);
        $actividades->save();

        //return redirect()->route('actividades.indexActividades')->with('success', 'Actividad actualizada con éxito');
        return redirect()->route('actividades.indexActividades', [
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'empleado_id' => $request->input('empleado_id')
        ])->with('success', 'Actividad actualizada con éxito.');
    }

    public function updateAvance(Request $request, $id)
    {
        // Obtener la actividad
        $actividad = Actividades::findOrFail($id);

        // Validar si la actividad está en estado PENDIENTE y se intenta finalizar
        if ($actividad->estado === 'PENDIENTE' && $request->input('avance') == 100) {
            return redirect()->route('actividades.indexActividades', [
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'empleado_id' => $request->input('empleado_id')
            ])
                ->withErrors(['error' => 'No se puede finalizar una actividad que no ha iniciado.']);
        }

        // Validar el avance
        $validated = $request->validate([
            'avance' => 'required|numeric|min:0|max:100',
        ]);

        // Actualizar el avance
        $actividad->avance = $validated['avance'];

        // Manejar los estados según el avance
        if ($actividad->avance == 0) {
            // Si está en pausa (PENDIENTE)
            $actividad->estado = 'PENDIENTE';

            // Calcular y acumular el tiempo transcurrido antes de pausar
            if (!is_null($actividad->tiempo_inicio)) {
                $inicio = \Carbon\Carbon::parse($actividad->tiempo_inicio)->setTimezone('America/Guayaquil');
                $fin = \Carbon\Carbon::now()->setTimezone('America/Guayaquil');
                $duracionMinutos = $fin->diffInMinutes($inicio);

                $actividad->tiempo_acumulado_minutos += $duracionMinutos;
                $actividad->tiempo_inicio = null; // Reiniciar el tiempo de inicio
            }
        } elseif ($actividad->avance > 0 && $actividad->avance < 100) {
            // Si está en curso (EN CURSO)
            $actividad->estado = 'EN CURSO';

            // Registrar el tiempo de inicio si no está en curso
            if (is_null($actividad->tiempo_inicio)) {
                $actividad->tiempo_inicio = now();
            }
        } elseif ($actividad->avance == 100) {
            // Si finaliza la actividad (FINALIZADO)
            $actividad->estado = 'FINALIZADO';

            // Calcular y acumular el tiempo transcurrido antes de finalizar
            if (!is_null($actividad->tiempo_inicio)) {
                $inicio = \Carbon\Carbon::parse($actividad->tiempo_inicio)->setTimezone('America/Guayaquil');
                $fin = \Carbon\Carbon::now()->setTimezone('America/Guayaquil');
                $duracionMinutos = $fin->diffInMinutes($inicio);

                $actividad->tiempo_acumulado_minutos += $duracionMinutos;
                $actividad->tiempo_inicio = null; // Reiniciar el tiempo de inicio
            }

            // Registrar la fecha de finalización
            $actividad->fecha_fin = now();

            // Convertir el tiempo acumulado a horas y minutos
            $horas = floor($actividad->tiempo_acumulado_minutos / 60);
            $minutos = $actividad->tiempo_acumulado_minutos % 60;

            $actividad->tiempo_real_horas = $horas;
            $actividad->tiempo_real_minutos = $minutos;
        }

        // Guardar los cambios
        $actividad->save();

        return redirect()->route('actividades.indexActividades', [
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'empleado_id' => $request->input('empleado_id')
        ])->with('success', 'Avance y estado actualizados con éxito.', request()->all());
    }

    public function updateObservaciones(Request $request, $id)
    {
        // Obtener la actividad
        $actividad = Actividades::findOrFail($id);

        // Verificar si el avance ya es 100; si es así, no permitir edición y mostrar un mensaje
        if ($actividad->avance == 100) {
            return redirect()->route('actividades.indexActividades', [
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'empleado_id' => $request->input('empleado_id')
            ])
                ->withErrors(['error' => 'La actividad ya está finalizada y no puede ser editada.']);
        }

        // Validar las observaciones
        $validated = $request->validate([
            'observaciones' => 'nullable|string',
        ]);

        $actividad->observaciones = $validated['observaciones'];
        $actividad->save();

        return redirect()->route('actividades.indexActividades', [
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'empleado_id' => $request->input('empleado_id')
        ])->with('success', 'Observaciones actualizadas con éxito.');
    }

    //Actualizar el tiempo real 

    public function updateTiempoReal(Request $request, $id)
    {
        // Validar los datos del formulario
        $validated = $request->validate([
            'tiempo_real_horas' => 'required|integer|min:0',
            'tiempo_real_minutos' => 'required|integer|min:0|max:59',
        ]);

        // Obtener la actividad
        $actividad = Actividades::findOrFail($id);

        // Actualizar el tiempo real
        $actividad->tiempo_real_horas = $validated['tiempo_real_horas'];
        $actividad->tiempo_real_minutos = $validated['tiempo_real_minutos'];

        // Calcular el tiempo acumulado en minutos
        $actividad->tiempo_acumulado_minutos = ($validated['tiempo_real_horas'] * 60) + $validated['tiempo_real_minutos'];

        // Guardar los cambios
        $actividad->save();

        return redirect()->route('actividades.indexActividades', [
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'empleado_id' => $request->input('empleado_id')
        ])->with('success', 'Tiempo real actualizado con éxito.');
    }


    public function startCounter($id)
    {

        $actividad = Actividades::findOrFail($id);

        // Solo iniciar el contador si el estado actual es "PENDIENTE"

        if ($actividad->estado === 'PENDIENTE') {

            $actividad->estado = 'EN CURSO';

            $actividad->fecha_inicio = now(); // Fecha actual para iniciar el contador

            $actividad->save();

            return redirect()->route('Actividades.indexActividades')->with('success', 'El contador ha iniciado.');
        }

        return redirect()->route('Actividades.indexActividades')->withErrors('El contador solo puede iniciarse si la actividad está pendiente.');
    }


    public function updateEstado(Request $request, $id)
    {
        $actividad = Actividades::findOrFail($id);

        // Finalizar actividad
        if ($request->has('finalizar')) {
            // Verificar si la actividad no ha iniciado
            if ($actividad->estado === 'PENDIENTE') {
                return redirect()->route('actividades.indexActividades', $request->only(['start_date', 'end_date', 'empleado_id']))
                    ->withErrors(['error' => 'No se puede finalizar una actividad que no ha iniciado.']);
            }

            $actividad->avance = 100;
            $actividad->estado = 'FINALIZADO';
            $actividad->fecha_fin = now();

            // Calcular y acumular tiempo antes de finalizar
            $this->actualizarTiempo($actividad);

            // Convertir tiempo acumulado a horas y minutos
            $horas = floor($actividad->tiempo_acumulado_minutos / 60);
            $minutos = $actividad->tiempo_acumulado_minutos % 60;

            $actividad->tiempo_real_horas = $horas;
            $actividad->tiempo_real_minutos = $minutos;

            $actividad->save();

            return redirect()->route('actividades.indexActividades', [
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'empleado_id' => $request->input('empleado_id')
            ])->with('success', 'Estado actualizado correctamente');
        }

        // Pausar actividad
        if ($request->has('pausar')) {
            $actividad->estado = 'PENDIENTE';

            // Calcular y acumular tiempo antes de pausar
            $this->actualizarTiempo($actividad);

            $actividad->save();

            return redirect()->route('actividades.indexActividades', [
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'empleado_id' => $request->input('empleado_id')
            ])->with('success', 'Actividad pausada con éxito.');
        }

        // Reanudar actividad
        if ($request->has('reanudar')) {
            if ($actividad->estado === 'PENDIENTE') {
                $actividad->estado = 'EN CURSO';
                $actividad->tiempo_inicio = now();
                $actividad->save();

                return redirect()->route('actividades.indexActividades', [
                    'start_date' => $request->input('start_date'),
                    'end_date' => $request->input('end_date'),
                    'empleado_id' => $request->input('empleado_id')
                ])->with('success', 'Actividad reanudada con éxito.');
            }

            return redirect()->route('actividades.indexActividades', $request->only(['start_date', 'end_date', 'empleado_id']))
                ->withErrors('Solo se pueden reanudar actividades en estado pendiente.');
        }

        return redirect()->route('actividades.indexActividades', $request->only(['start_date', 'end_date', 'empleado_id']))
            ->withErrors('Operación no válida.');
    }

    // Método para exportar actividades a Excel
    public function exportarServicioHora($formato)
    {
        // Obtener actividades con producto "Servicio por Hora"
        $actividades = Actividades::with(['cliente', 'empleado', 'producto'])
            ->whereHas('producto', function ($query) {
                $query->where('nombre', 'like', '%Servicio por Hora%');
            })
            ->get();

        if ($formato === 'excel') {
            return Excel::download(
                new ActividadesExport($actividades),
                'servicio_por_hora_' . now()->format('Ymd') . '.xlsx'
            );
        } else {
            return \Barryvdh\DomPDF\Facade\Pdf::loadView('Actividades.reporte_servicio_hora', compact('actividades'))
                ->download('servicio_por_hora_' . now()->format('Ymd') . '.pdf');
        }
    }
    
    /**
     * Método para calcular y acumular tiempo transcurrido.
     */
    private function actualizarTiempo($actividad)
    {
        if (!is_null($actividad->tiempo_inicio)) {
            $inicio = \Carbon\Carbon::parse($actividad->tiempo_inicio)->setTimezone('America/Guayaquil');
            $fin = \Carbon\Carbon::now()->setTimezone('America/Guayaquil');
            $duracionMinutos = $fin->diffInMinutes($inicio);

            $actividad->tiempo_acumulado_minutos += $duracionMinutos;
            $actividad->tiempo_inicio = null; // Reiniciar tiempo inicio
        }
    }

    public function show($id)
    {
        $actividades = Actividades::with('cliente', 'empleado', 'departamento')->findOrFail($id);

        // Si es una solicitud AJAX, devuelve el parcial
        if (request()->ajax()) {
            return view('Actividades.partials.show-content', compact('actividades'));
        }

        // De lo contrario, devuelve la vista completa
        return view('Actividades.show', compact('actividades'));
    }

    public function destroy($id)
    {
        $actividad = Actividades::findOrFail($id);
        $actividad->delete();

        return redirect()->route('actividades.indexActividades', [
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'empleado_id' => $request->input('empleado_id')
        ])->with('success', 'Actividad eliminada con éxito.');
    }
}
