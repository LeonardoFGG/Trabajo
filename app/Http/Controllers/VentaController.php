<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Paquete;
use App\Models\Empleados;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    /**
     * Display a listing of the ventas.
     */
    public function index()
    {
        $ventas = Venta::with(['cliente', 'empleado'])->latest()->paginate(10);
        $clientes = Cliente::all();
        $empleados = Empleados::all();
        $productos = Producto::where('activo', 1)->get();
        $paquetes = Paquete::all();
        
        return view('ventas.index', compact('ventas', 'clientes', 'empleados', 'productos', 'paquetes'));
    }

    /**
     * Store a newly created venta in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tipo_venta' => 'required|in:Interna,Externa',
            'cliente_id' => 'required|exists:clientes,id',
            'empleado_id' => 'required|exists:empleados,id',
            'tipo_item_venta' => 'nullable|in:producto,paquete',
            'producto_id' => 'nullable|exists:productos,id',
            'paquete_id' => 'nullable|exists:paquetes,id',
            'detalle_prospeccion' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('ventas.index')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            
            $venta = Venta::create([
                'tipo_venta' => $request->tipo_venta,
                'cliente_id' => $request->cliente_id,
                'empleado_id' => $request->empleado_id,
                'estado_comercial' => 'Prospección', // Estado inicial
                'estado' => 'Pendiente', // Estado inicial
                'tipo_item_venta' => $request->tipo_item_venta,
                'producto_id' => $request->producto_id,
                'paquete_id' => $request->paquete_id,
                'detalle_prospeccion' => $request->detalle_prospeccion,
            ]);
            
            // Si hay productos seleccionados
            if ($request->productos) {
                foreach ($request->productos as $productoId => $cantidad) {
                    if ($cantidad > 0) {
                        $venta->productos()->attach($productoId, [
                            'cantidad' => $cantidad,
                            'notas' => $request->notas_producto[$productoId] ?? null
                        ]);
                    }
                }
            }
            
            // Si hay paquetes seleccionados
            if ($request->paquetes) {
                foreach ($request->paquetes as $paqueteId => $cantidad) {
                    if ($cantidad > 0) {
                        $venta->paquetes()->attach($paqueteId, [
                            'cantidad' => $cantidad,
                            'notas' => $request->notas_paquete[$paqueteId] ?? null
                        ]);
                    }
                }
            }
            
            DB::commit();
            
            return redirect()->route('ventas.index')
                ->with('success', 'Venta creada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('ventas.index')
                ->with('error', 'Error al crear la venta: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified venta.
     */
    public function show(Venta $venta)
    {
        $venta->load(['cliente', 'empleado', 'productos', 'paquetes']);
        return view('ventas.show', compact('venta'));
    }

    /**
     * Show the form for editing the specified venta.
     */
    public function edit(Venta $venta)
    {
        $clientes = Cliente::all();
        $empleados = Empleados::all();
        $productos = Producto::where('activo', 1)->get();
        $paquetes = Paquete::all();
        
        return view('ventas.edit', compact('venta', 'clientes', 'empleados', 'productos', 'paquetes'));
    }

    /**
     * Update the specified venta in storage.
     */
    public function update(Request $request, Venta $venta)
    {
        $validator = Validator::make($request->all(), [
            'tipo_venta' => 'required|in:Interna,Externa',
            'cliente_id' => 'required|exists:clientes,id',
            'empleado_id' => 'required|exists:empleados,id',
            'tipo_item_venta' => 'nullable|in:producto,paquete',
            'producto_id' => 'nullable|exists:productos,id',
            'paquete_id' => 'nullable|exists:paquetes,id',
        ]);

        if ($validator->fails()) {
            return redirect()->route('ventas.edit', $venta)
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            
            $venta->update($request->all());
            
            // Actualizar productos
            if ($request->productos) {
                $venta->productos()->detach();
                foreach ($request->productos as $productoId => $cantidad) {
                    if ($cantidad > 0) {
                        $venta->productos()->attach($productoId, [
                            'cantidad' => $cantidad,
                            'notas' => $request->notas_producto[$productoId] ?? null
                        ]);
                    }
                }
            }
            
            // Actualizar paquetes
            if ($request->paquetes) {
                $venta->paquetes()->detach();
                foreach ($request->paquetes as $paqueteId => $cantidad) {
                    if ($cantidad > 0) {
                        $venta->paquetes()->attach($paqueteId, [
                            'cantidad' => $cantidad,
                            'notas' => $request->notas_paquete[$paqueteId] ?? null
                        ]);
                    }
                }
            }
            
            DB::commit();
            
            return redirect()->route('ventas.index')
                ->with('success', 'Venta actualizada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('ventas.edit', $venta)
                ->with('error', 'Error al actualizar la venta: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified venta from storage.
     */
    public function destroy(Venta $venta)
    {
        try {
            $venta->delete();
            return redirect()->route('ventas.index')
                ->with('success', 'Venta eliminada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('ventas.index')
                ->with('error', 'Error al eliminar la venta: ' . $e->getMessage());
        }
    }

    /**
     * Pausar una venta
     */
    public function pausar(Venta $venta)
    {
        try {
            $venta->pausar();
            return redirect()->back()->with('success', 'Venta pausada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al pausar la venta: ' . $e->getMessage());
        }
    }

    /**
     * Reanudar una venta pausada
     */
    public function reanudar(Venta $venta)
    {
        try {
            $venta->reanudar();
            return redirect()->back()->with('success', 'Venta reanudada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al reanudar la venta: ' . $e->getMessage());
        }
    }

    /**
     * Avanzar al siguiente estado comercial en el workflow
     */
    public function avanzarEstado(Request $request, Venta $venta)
    {
        $validator = Validator::make($request->all(), [
            'nuevo_estado' => 'required|in:Prospección,Contacto,Presentación,Propuesta,Negociación,Cierre',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Verificar si el avance es válido
            if (!$venta->puedeAvanzarA($request->nuevo_estado)) {
                return redirect()->back()->with('error', 'No se puede avanzar a este estado desde el estado actual.');
            }

            // Actualizar los datos específicos del nuevo estado
            switch ($request->nuevo_estado) {
                case 'Contacto':
                    $venta->fecha_contacto = $request->fecha_contacto ?? now();
                    $venta->detalle_contacto = $request->detalle_contacto;
                    $venta->canal_comunicacion = $request->canal_comunicacion;
                    break;
                case 'Presentación':
                    $venta->fecha_presentacion = $request->fecha_presentacion ?? now();
                    $venta->observacion_presentacion = $request->observacion_presentacion;
                    break;
                case 'Propuesta':
                    $venta->fecha_propuesta = $request->fecha_propuesta ?? now();
                    $venta->detalle_propuesta = $request->detalle_propuesta;
                    
                    // Manejar carga de archivo si existe
                    if ($request->hasFile('archivo_propuesta')) {
                        $archivo = $request->file('archivo_propuesta');
                        $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
                        $archivo->storeAs('propuestas', $nombreArchivo, 'public');
                        $venta->archivo_propuesta = $nombreArchivo;
                    }
                    break;
                case 'Negociación':
                    $venta->fecha_negociacion = $request->fecha_negociacion ?? now();
                    $venta->detalle_negociacion = $request->detalle_negociacion;
                    
                    // Manejar carga de archivo si existe
                    if ($request->hasFile('archivo_negociacion')) {
                        $archivo = $request->file('archivo_negociacion');
                        $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
                        $archivo->storeAs('negociaciones', $nombreArchivo, 'public');
                        $venta->archivo_negociacion = $nombreArchivo;
                    }
                    break;
                case 'Cierre':
                    $venta->fecha_venta = $request->fecha_venta ?? now();
                    $venta->fecha_contrato = $request->fecha_contrato;
                    $venta->fecha_cobro = $request->fecha_cobro;
                    $venta->fecha_expiracion = $request->fecha_expiracion;
                    
                    // Cambiar estado general a "En Curso" o "Finalizada" según corresponda
                    $venta->estado = ($request->estado_final) ?? 'En Curso';
                    
                    // Manejar carga de archivo si existe
                    if ($request->hasFile('anexo_contrato')) {
                        $archivo = $request->file('anexo_contrato');
                        $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
                        $archivo->storeAs('contratos', $nombreArchivo, 'public');
                        $venta->anexo_contrato = $nombreArchivo;
                    }
                    break;
            }

            // Avanzar el estado comercial
            $venta->avanzarEstadoComercial($request->nuevo_estado);
            $venta->save();
            
            return redirect()->back()->with('success', 'Estado de venta actualizado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al actualizar el estado: ' . $e->getMessage());
        }
    }
}