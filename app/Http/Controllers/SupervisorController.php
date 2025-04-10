<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supervisor;
use App\Models\Empleados;
use App\Models\Departamento;

class SupervisorController extends Controller
{
    public function index()
    {
        $supervisores = Supervisor::with('empleados')->get();
        return view('Supervisores.index', compact('supervisores'));
    }

    public function getSupervisoresPorDepartamento($departamentoId)
    {
        // Obtener los supervisores del departamento seleccionado
        $supervisores = Supervisor::where('departamento_id', $departamentoId)->get();

        // Devolver los supervisores en formato JSON
        return response()->json(['supervisores' => $supervisores]);
    }

    public function create()
    {
        $empleados = Empleados::all();
        $departamentos = Departamento::all();
        $supervisores = Supervisor::all();
        return view('Supervisores.create', compact('empleados', 'departamentos', 'supervisores'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_supervisor' => 'required|string|max:255',
            'empleado_id' => 'required|exists:empleados,id',
            'departamento_id' => 'required|exists:departamentos,id',
            'es_supervisor_superior' => $request->input('es_supervisor_superior', false), // Por defecto, no es supervisor superior
        ]);

        $supervisor = new Supervisor($validated);
        $supervisor->save();

        return redirect()->route('Supervisores.index')->with('success', 'Supervisor creado con éxito.');
    }

    public function show($id)
    {
         $supervisor = Supervisor::with('empleados', 'departamento')->findOrFail($id);
        return view('Supervisores.show', compact('supervisor'));
    }

    public function edit($id)
    {
        $supervisor = Supervisor::findOrFail($id);
        $empleados = Empleados::all();
        $departamentos = Departamento::all();
        $supervisores = Supervisor::all();
        return view('Supervisores.edit', compact('supervisor', 'empleados', 'departamentos', 'supervisores'));
    }

    public function update(Request $request, $id)
    {
        $supervisor = Supervisor::findOrFail($id);

        $validated = $request->validate([
            'nombre_supervisor' => 'required|string|max:255',
            'empleado_id' => 'required|exists:empleados,id',
            'departamento_id' => 'required|exists:departamentos,id',
            'supervisor_id' => 'nullable|exists:supervisores,id',
        ]);

        $supervisor->fill($validated);
        $supervisor->save();

        return redirect()->route('Supervisores.index')->with('success', 'Supervisor actualizado con éxito.');
    }

    public function destroy($id)
    {
        $supervisor = Supervisor::findOrFail($id);
        $supervisor->delete();

        return redirect()->route('Supervisores.index')->with('success', 'Supervisor eliminado con éxito.');
    }

    /**
     * Asignar un supervisor superior a un supervisor.
     */
    public function asignarSupervisorSuperior(Request $request)
    {
        $request->validate([
            'supervisor_id' => 'required|exists:supervisores,id',
            'supervisor_superior_id' => 'nullable|exists:supervisores,id',
        ]);

        $supervisor = Supervisor::findOrFail($request->supervisor_id);
        $supervisor->supervisor_id = $request->supervisor_superior_id;
        $supervisor->save();

        return response()->json([
            'message' => 'Supervisor superior asignado correctamente.',
            'supervisor' => $supervisor,
        ]);
    }

    /**
     * Obtener los subordinados de un supervisor superior.
     */
    public function obtenerSubordinados($id)
    {
        $supervisorSuperior = Supervisor::findOrFail($id);
        $subordinados = $supervisorSuperior->supervisoresSubordinados;

        return response()->json([
            'supervisor_superior' => $supervisorSuperior,
            'subordinados' => $subordinados,
        ]);
    }
}
