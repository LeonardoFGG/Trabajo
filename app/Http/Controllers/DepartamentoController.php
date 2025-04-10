<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Departamento;
use App\Models\Supervisor;


class DepartamentoController extends Controller
{



    public function index()
    {
        $departamentos = Departamento::with('supervisor')->get();
        return view('Departamentos.index', compact('departamentos'));
    }



    public function create()
    {
        $supervisores = Supervisor::all();
        return view('Departamentos.create', compact('supervisores'));
    }

    public function asignarSupervisor()
    {
        // Obtener el ID del supervisor
        $supervisorId = Supervisor::where('nombre_supervisor', 'Empleado1 Empleado1')->value('id');

        if ($supervisorId) {
            // Actualizar el supervisor_id en el departamento
            Departamento::where('id', 1) // Asegúrate de que sea el ID correcto del departamento
                ->update([
                    'supervisor_id' => $supervisorId,
                ]);

            return response()->json(['message' => 'Supervisor asignado correctamente']);
        } else {
            return response()->json(['message' => 'No se encontró el supervisor'], 404);
        }
    }

    public function getSupervisor($id)
    {
        $departamento = Departamento::with('supervisor')->find($id);
        return response()->json([
            'supervisor' => $departamento->supervisor ? $departamento->supervisor->nombre_supervisor : 'Sin asignar'
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'supervisor_id' => 'nullable|exists:supervisores,id',
        ]);

        Departamento::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'supervisor_id' => $request->supervisor_id,
        ]);

        return redirect()->route('departamentos.index')->with('success', 'Departamento creado con éxito.');
    }

    public function show($id)
    {
        $departamento = Departamento::with('cargos')->findOrFail($id);
        return view('Departamentos.show', compact('departamento'));
    }

    public function edit($id)
    {
        $departamento = Departamento::findOrFail($id);
        return view('Departamentos.edit', compact('departamento'));
    }

    public function update(Request $request, $id)
    {
        $departamento = Departamento::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:255',
            
        ]);

        $departamento->fill($validated);
        $departamento->save();

        return redirect()->route('departamentos.index')->with('success', 'Departamento actualizado con éxito.');
    }

    public function destroy($id)
    {
        $departamento = Departamento::findOrFail($id);
        $departamento->delete();

        return redirect()->route('departamentos.index')->with('success', 'Departamento eliminado con éxito.');
    }
}
