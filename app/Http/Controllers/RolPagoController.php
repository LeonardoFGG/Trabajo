<?php

namespace App\Http\Controllers;



use App\Models\Empleados;
use App\Models\RolPago;
use App\Models\Rubro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;



class RolPagoController extends Controller
{
    public function index(Request $request)
    {
        $rolesPago = RolPago::with('empleado', 'rubros')
            ->when($request->search, function ($query) use ($request) {
                return $query->whereHas('empleado', function ($query) use ($request) {
                    $query->where('nombre1', 'like', '%' . $request->search . '%');
                });
            })
            ->paginate(10);  // Usar paginate si necesitas paginación

        return view('Roles_Pago.index', compact('rolesPago'));
    }

    public function create()
    {
        $rubros = Rubro::all();
        $empleados = Empleados::all();
        return view('Roles_Pago.create', compact('rubros', 'empleados'));
    }

    public function store(Request $request)
    {
        // Validaciones
        $request->validate([
            'empleado_id' => 'required|exists:empleados,id',
            'rubros' => 'required|array', // los rubros deben ser un array
            'rubros.*' => 'exists:rubros,id', // cada rubro debe existir
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date',
            'total_ingreso' => 'required|numeric',
            'total_egreso' => 'required|numeric',
            'salario_neto' => 'required|numeric',
        ]);

        // Crear el Rol de Pago
        $rolPago = new RolPago();
        $rolPago->empleado_id = $request->empleado_id;
        $rolPago->fecha_inicio = $request->fecha_inicio;
        $rolPago->fecha_fin = $request->fecha_fin;

        // Calcular los totales de ingreso y egreso
        $totalIngreso = 0;
        $totalEgreso = 0;

        
        $rolPago->save();

        // Asociar los rubros seleccionados con el rol de pago en la tabla 'empleado_rubro'
        $rolPago->rubros()->attach($request->rubros);

        // Redirigir con un mensaje de éxito
        return redirect()->route('Roles_Pago.index')->with('success', 'Rol de pago creado con éxito.');
    }


    public function show(RolPago $rolPago)
    {
        $rolesPago = RolPago::with('empleado', 'rubros')->get();

        return view('Roles_Pago.show', compact('rolPago'));
    }

    public function edit(RolPago $rolPago)
    {
        $empleados = Empleados::all();
        $rubros = Rubro::all();
        return view('Roles_Pago.edit', compact('rolPago', 'empleados', 'rubros'));
    }

    public function update(Request $request, RolPago $rolespago)
    {
        $request->validate([
            'rubros' => 'required|array',
            'rubros.*' => 'exists:rubros,id',
            'empleado_id' => 'required|exists:empleados,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'total_ingreso' => 'required|numeric',
            'total_egreso' => 'required|numeric',
            'salario_neto' => 'required|numeric',
        ]);

        // Actualizar el Rol de Pago con los nuevos datos
        $rolespago->update($request->only('empleado_id', 'fecha_inicio', 'fecha_fin', 'total_ingreso', 'total_egreso', 'salario_neto'));

        // Sincronizar los rubros seleccionados para el rol de pago
        $rolespago->rubros()->sync($request->rubros);

        return redirect()->route('Roles_Pago.index')->with('success', 'Rol de pago actualizado exitosamente.');
    }

    public function destroy(RolPago $rolesPago)
    {
        $rolesPago->delete();
        return redirect()->route('Roles_Pago.index')->with('success', 'Rol de pago eliminado exitosamente');
    }
}
