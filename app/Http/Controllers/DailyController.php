<?php

namespace App\Http\Controllers;

use App\Models\Daily;
use App\Models\Empleados;
use App\Models\Departamento;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DailyController extends Controller
{
    /**
     * Show a form to create a new daily scrum entry.
     */
    public function create()
    {
        $daily = Daily::all();
        $empleados = Empleados::all(); // Fetch all employees for the selection
        return view('Daily.create', compact('daily', 'empleados'));
    }

    /**
     * Store a newly created daily scrum entry in the database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'empleado_id' => 'required|exists:empleados,id',
            'ayer' => 'required|string',
            'hoy' => 'required|string',
            'dificultades' => 'nullable|string',
        ]);

        // Create the Daily entry with the current date for 'fecha'
        Daily::create([
            'empleado_id' => $request->empleado_id,
            'fecha' => now()->toDateString(), // Fecha actual
            'ayer' => $request->ayer,
            'hoy' => $request->hoy,
            'dificultades' => $request->dificultades,
        ]);


        return redirect()->route('daily.index')->with('success', 'Daily Scrum se ha creado correctamente.');
    }

    /**
     * Display a list of daily scrum entries.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $empleadoId = $request->input('empleado_id');
        $departamentoId = $request->input('departamento_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $dailiesQuery = Daily::query();

        // Filtrar por rango de fechas si ambos parámetros existen
        if ($startDate && $endDate) {
            $dailiesQuery->whereBetween('fecha', [$startDate, $endDate]);
        }
        // O solo por una fecha específica si solo se proporciona una
        elseif ($startDate) {
            $dailiesQuery->whereDate('fecha', $startDate);
        }

        if ($departamentoId) {
            $dailiesQuery->whereHas('empleado', function ($query) use ($departamentoId) {
                $query->where('departamento_id', $departamentoId);
            });
        }

        if ($empleadoId) {
            $dailiesQuery->where('empleado_id', $empleadoId);
        }

        // Ordenar siempre por fecha y creación descendente (últimos primero)
        // Ordenar por fecha y creación descendente
        if (!$startDate || $departamentoId || $empleadoId) {
            $dailiesQuery->orderBy('fecha', 'desc')->orderBy('created_at', 'desc');
        }

        // Paginar los resultados
        $dailies = $dailiesQuery->paginate();

        $empleado = $empleadoId ? Empleados::find($empleadoId) : null;
        $departamentos = Departamento::all();
        $empleados = $departamentoId ?
            Empleados::where('departamento_id', $departamentoId)->get() :
            Empleados::all();

        return view('Daily.index', compact(
            'dailies',
            'empleado',
            'departamentos',
            'empleados',
            'departamentoId',
            'startDate',
            'endDate'
        ));
    }
    /**
     * Show the form to edit a daily scrum entry.
     */

    public function edit(Daily $daily)
    {
        $empleados = Empleados::all(); // Fetch all employees for the selection
        return view('Daily.edit', compact('daily', 'empleados'));
    }


    /**
     * Update the daily scrum entry in the database.
     */

    public function update(Request $request, Daily $daily)
    {
        $request->validate([
            'empleado_id' => 'required|exists:empleados,id',
            'ayer' => 'required|string',
            'hoy' => 'required|string',
            'dificultades' => 'nullable|string',
        ]);

        $daily->update($request->all());

        return redirect()->route('daily.index')->with('success', 'Daily Scrum se ha actualizado correctamente.');
    }

    public function show(Daily $daily)
    {
        return view('Daily.show', compact('daily'));
    }


    /**
     * Delete the daily scrum entry from the database.
     */

    public function destroy(Daily $daily)
    {
        $daily->delete(); // Elimina el registro
        return redirect()->route('daily.index')->with('success', 'Daily Scrum se ha eliminado correctamente.');
    }
}
