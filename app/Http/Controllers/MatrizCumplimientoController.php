<?php

namespace App\Http\Controllers;

use App\Models\Empleados;
use App\Models\MatrizCumplimiento;
use App\Models\Parametro;
use App\Models\Departamento;
use App\Models\Cargos;
use App\Models\Supervisor;
use Illuminate\Http\Request;

class MatrizCumplimientoController extends Controller
{
   public function index(Request $request)
{
    $user = auth()->user();
    $empleado_id = $request->input('empleado_id');

    // Validar rol y obtener empleados visibles según supervisor
    if ($user->isAdmin()) {
        $empleados = Empleados::paginate(10); // Todos los empleados para el administrador
        $cumplimientosQuery = MatrizCumplimiento::with('empleado', 'cargo', 'supervisor')
            ->orderBy('created_at', 'desc');
    } elseif ($user->isEmpleado() && $user->empleado->es_supervisor) {
        $empleados = Empleados::where('supervisor_id', $user->empleado->id)->get(); // Empleados supervisados
        $cumplimientosQuery = MatrizCumplimiento::with('empleado', 'cargo', 'supervisor')
            ->whereIn('empleado_id', $empleados->pluck('id'))
            ->orderBy('created_at', 'desc');
    } else {
        return redirect()->route('home')->with('error', 'No tienes permiso para acceder a esta sección.');
    }

    // Filtrar por empleado seleccionado
    if ($empleado_id) {
        $cumplimientosQuery->where('empleado_id', $empleado_id);
    }

    // Paginar después de aplicar los filtros
    $cumplimientos = $cumplimientosQuery->paginate(10);

    return view('Matriz_Cumplimientos.index', compact('cumplimientos', 'empleados', 'empleado_id'));
}





    public function create(Request $request)
{
    $user = auth()->user();

    // Obtener empleados según rol
    if ($user->isAdmin()) {
        $empleados = Empleados::with('cargo', 'supervisor')->get(); // Cargar relaciones
    } elseif ($user->isEmpleado() && $user->empleado->es_supervisor) {
        $empleados = Empleados::with('cargo', 'supervisor')
            ->where('supervisor_id', $user->empleado->id)
            ->get();
    } else {
        $empleados = collect(); // Colección vacía para usuarios no autorizados
    }

    $parametros = Parametro::all();
    $cargos = Cargos::all();
    $supervisores = Supervisor::all();

    return view('Matriz_Cumplimientos.create', compact('empleados', 'parametros', 'cargos', 'supervisores'));
}




    public function store(Request $request)
{
    $request->validate([
       'parametro_id' => [
        'required',
        'exists:parametros,id',
        function ($attribute, $value, $fail) use ($request) {
            $empleado = Empleados::find($request->empleado_id);
            $parametro = Parametro::find($value);

            if ($parametro->departamento_id !== $empleado->departamento_id) {
                $fail('El parámetro seleccionado no pertenece al departamento del empleado.');
            }
        },
    ],
       'puntos' => 'required|numeric|min:0.5|regex:/^\d+(\.\d{1})?$/',
        'empleado_id' => 'required|exists:empleados,id',
        'cargo_id' => 'required|exists:cargos,id',
        'supervisor_id' => 'required|exists:supervisores,id',
    ]);

    // Store the data
    MatrizCumplimiento::create($request->all());

    return redirect()->route('matriz_cumplimientos.index')->with('success', 'Cumplimiento registrado correctamente.');
}

public function show(MatrizCumplimiento $matriz_cumplimiento)
{
    return view('Matriz_Cumplimientos.show', compact('matriz_cumplimiento'));
}


public function edit($id)
{
    // Buscar el cumplimiento específico
    $cumplimiento = MatrizCumplimiento::findOrFail($id);

    // Obtener los empleados, cargos, supervisores y parámetros
    $empleados = Empleados::all();
    $cargos = Cargos::all();
    $supervisores = Supervisor::all();
    $parametros = Parametro::all();

    // Retornar la vista con las variables necesarias
    return view('Matriz_Cumplimientos.edit', compact('cumplimiento', 'empleados', 'cargos', 'supervisores', 'parametros'));
}

public function update(Request $request, MatrizCumplimiento $cumplimiento)
{
    $validated = $request->validate([
        'parametro_id' => 'required|exists:parametros,id',
        'puntos' => 'required|numeric|min:0.5|regex:/^(\d+(\.5)?)$/', // Asegurando que los puntos sean múltiplos de 0.5
        'empleado_id' => 'required|exists:empleados,id',
        'cargo_id' => 'required|exists:cargos,id',
        'supervisor_id' => 'required|exists:supervisores,id',
    ]);

    \Log::info('Validado:', ['data' => $validated]);

    // Sumar los puntos actuales con los nuevos
    $nuevo_punto = $cumplimiento->puntos + $request->puntos;

    // Verifica si los valores son correctos antes de actualizar
    \Log::info('Nuevo Punto:', ['puntos' => $nuevo_punto]);

    // Actualiza el cumplimiento con los nuevos datos
    $cumplimiento->update([
        'parametro_id' => $request->parametro_id,
        'puntos' => $nuevo_punto, // Actualización de puntos con la suma
        'empleado_id' => $request->empleado_id,
        'cargo_id' => $request->cargo_id,
        'supervisor_id' => $request->supervisor_id,
    ]);

    return redirect()->route('matriz_cumplimientos.index')->with('success', 'Matriz de Cumplimientos actualizada correctamente.');
}



public function updatePuntos(Request $request, $id)
{
    $request->validate([
        'puntos' => 'required|numeric|min:0|regex:/^\d+(\.\d{1})?$/', // Acepta solo un decimal
    ]);

    // Buscar el cumplimiento
    $cumplimiento = MatrizCumplimiento::findOrFail($id);

    // Validar que los puntos nuevos no sean menores que los actuales
    if ($request->puntos < $cumplimiento->puntos) {
        return redirect()->route('matriz_cumplimientos.index')->with('error', 'No se pueden reducir los puntos, solo aumentarlos.');
    }

    // Actualizar los puntos
    $cumplimiento->update(['puntos' => $request->puntos]);

    return redirect()->route('matriz_cumplimientos.index')->with('success', 'Puntos actualizados correctamente.');
}


   public function destroy($id)
{
    // Buscar el registro por ID
    $cumplimiento = MatrizCumplimiento::findOrFail($id);
    
    // Eliminar el registro
    $cumplimiento->delete();

    // Redirigir con un mensaje de éxito
    return redirect()->route('matriz_cumplimientos.index')->with('success', 'Matriz de cumplimiento eliminada con éxito.');
}


}
