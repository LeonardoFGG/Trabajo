<?php

namespace App\Http\Controllers;

use App\Models\Parametro;
use App\Models\Departamento;
use Illuminate\Http\Request;

class ParametroController extends Controller
{
    public function index()
    {
        $parametros = Parametro::with('departamento')->get();
     
        return view('Parametros.index', compact('parametros'));
    }

    public function create()
    {	
        $departamentos = Departamento::all();
        return view('Parametros.create ', compact('departamentos'));
    }

    public function store(Request $request)
    {
       $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:parametros,nombre',
            'departamento_id' => 'required|exists:departamentos,id',
        ]);
	
	$parametro = new Parametro($validated);
	$parametro->save();

        return redirect()->route('parametros.index')->with('success', 'Parámetro creado correctamente.');
    }

    public function show($id)
{
    $parametro = Parametro::findOrFail($id);
    return view('Parametros.show',compact('parametro'));

}

    public function edit(Parametro $parametro)
    {
        $departamentos = Departamento::all();
        return view('Parametros.edit', compact('parametro', 'departamentos'));
    }

    public function update(Request $request, Parametro $parametro)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:parametros,nombre,' . $parametro->id,
            'departamento_id' => 'required|exists:departamentos,id',
        ]);

        $parametro->update($request->all());

        return redirect()->route('parametros.index')->with('success', 'Parámetro actualizado correctamente.');
    }

    public function destroy(Parametro $parametro)
    {
        $parametro->delete();

        return redirect()->route('parametros.index')->with('success', 'Parámetro eliminado.');
    }
}
