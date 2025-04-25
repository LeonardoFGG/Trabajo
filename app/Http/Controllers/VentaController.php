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
     * Show the form for creating a new venta.
     */
    public function create()
    {
        $clientes = Cliente::all();
        $empleados = Empleados::all();
        $productos = Producto::where('activo', 1)->get();
        $paquetes = Paquete::all();

        return response()->json([
            'success' => true,
            'clientes' => $clientes,
            'empleados' => $empleados,
            'productos' => $productos,
            'paquetes' => $paquetes,
            'html' => view('ventas.partials.create', compact('clientes', 'empleados', 'productos', 'paquetes'))->render()
        ]);
    }

    /**
     * Store a newly created venta in storage.
     */
    public function store(Request $request)
    {
        // Validar datos
        $validated = $request->validate([
            'tipo_venta' => 'required|in:Interna,Externa',
            'estado_comercial' => 'required|in:Prospección,Contacto,Presentación,Propuesta,Negociación,Cierre',
            'cliente_id' => 'required_if:tipo_venta,Interna',
            'empleado_id' => 'required|exists:empleados,id',
            'estado' => 'nullable|in:Pendiente,Activa,Inactiva,Expirada,Cancelada,En Curso,Finalizada,Pausada',
            'tipo_item_venta' => 'nullable|in:producto,paquete',
            'producto_id' => 'nullable|required_if:tipo_item_venta,producto|exists:productos,id',
            'paquete_id' => 'nullable|required_if:tipo_item_venta,paquete|exists:paquetes,id',

            // Campos según estado comercial
            'detalle_prospeccion' => 'nullable|string',
            'fecha_contacto' => 'nullable|date',
            'detalle_contacto' => 'nullable|string',
            'canal_comunicacion' => 'nullable|string',
            'fecha_presentacion' => 'nullable|date',
            'observacion_presentacion' => 'nullable|string',
            'fecha_propuesta' => 'nullable|date',
            'archivo_propuesta' => 'nullable|file|mimes:pdf,doc,docx',
            'detalle_propuesta' => 'nullable|string',
            'fecha_negociacion' => 'nullable|date',
            'archivo_negociacion' => 'nullable|file|mimes:pdf,doc,docx',
            'detalle_negociacion' => 'nullable|string',
            'fecha_venta' => 'nullable|date',
            'fecha_contrato' => 'nullable|date',
            'fecha_cobro' => 'nullable|date',
            'fecha_expiracion' => 'nullable|date',
            'anexo_contrato' => 'nullable|file|mimes:pdf,doc,docx',

            // Campos para cliente externo
            'nombre' => 'required_if:tipo_venta,Externa',
            'telefono' => 'required_if:tipo_venta,Externa',
            'email' => 'nullable|email',
            'direccion' => 'nullable|string',
            'contacto' => 'nullable|string',

            // Arrays para productos y paquetes adicionales
            'productos' => 'nullable|array',
            'productos.*' => 'nullable|integer|min:0',
            'notas_producto' => 'nullable|array',
            'notas_producto.*' => 'nullable|string',
            'paquetes' => 'nullable|array',
            'paquetes.*' => 'nullable|integer|min:0',
            'notas_paquete' => 'nullable|array',
            'notas_paquete.*' => 'nullable|string',
        ]);

        // Determinar acción y estado
        $accion = $request->input('accion', 'finalizar');
        $estado = $request->input('estado', 'Pendiente');

        if ($accion === 'finalizar') {
            $estado = 'Finalizada';
        } elseif ($accion === 'pausar') {
            $estado = 'Pausada';
        } elseif ($accion === 'activar') {
            $estado = 'Activa';
        }

        try {
            DB::beginTransaction();

            // Si es una venta externa, crear nuevo cliente
            if ($request->input('tipo_venta') === 'Externa') {
                $cliente = Cliente::create([
                    'nombre' => $request->input('nombre'),
                    'telefono' => $request->input('telefono'),
                    'email' => $request->input('email'),
                    'direccion' => $request->input('direccion'),
                    'contacto' => $request->input('contacto'),
                ]);
                $cliente_id = $cliente->id;
            } else {
                $cliente_id = $request->input('cliente_id');
            }

            // Preparar datos para la venta según el estado comercial
            $ventaData = [
                'tipo_venta' => $request->input('tipo_venta'),
                'cliente_id' => $cliente_id,
                'empleado_id' => $request->input('empleado_id'),
                'estado_comercial' => $request->input('estado_comercial'),
                'estado' => $estado,
                'tipo_item_venta' => $request->input('tipo_item_venta'),
                'producto_id' => $request->input('tipo_item_venta') === 'producto' ? $request->input('producto_id') : null,
                'paquete_id' => $request->input('tipo_item_venta') === 'paquete' ? $request->input('paquete_id') : null,
            ];

            // Agregar campos específicos según el estado comercial
            switch ($request->input('estado_comercial')) {
                case 'Prospección':
                    $ventaData['detalle_prospeccion'] = $request->input('detalle_prospeccion');
                    break;

                case 'Contacto':
                    $ventaData['fecha_contacto'] = $request->input('fecha_contacto');
                    $ventaData['detalle_contacto'] = $request->input('detalle_contacto');
                    $ventaData['canal_comunicacion'] = $request->input('canal_comunicacion');
                    break;

                case 'Presentación':
                    $ventaData['fecha_presentacion'] = $request->input('fecha_presentacion');
                    $ventaData['observacion_presentacion'] = $request->input('observacion_presentacion');
                    break;

                case 'Propuesta':
                    $ventaData['fecha_propuesta'] = $request->input('fecha_propuesta');
                    $ventaData['detalle_propuesta'] = $request->input('detalle_propuesta');
                    break;

                case 'Negociación':
                    $ventaData['fecha_negociacion'] = $request->input('fecha_negociacion');
                    $ventaData['detalle_negociacion'] = $request->input('detalle_negociacion');
                    break;

                case 'Cierre':
                    $ventaData['fecha_venta'] = $request->input('fecha_venta') ?? now();
                    $ventaData['fecha_contrato'] = $request->input('fecha_contrato');
                    $ventaData['fecha_cobro'] = $request->input('fecha_cobro');
                    $ventaData['fecha_expiracion'] = $request->input('fecha_expiracion');
                    break;
            }

            // Crear venta
            $venta = Venta::create($ventaData);

            // Manejar productos/paquetes según el paso alcanzado
            if (in_array($request->input('estado_comercial'), ['Presentación', 'Propuesta', 'Negociación', 'Cierre'])) {
                // Los productos y paquetes principales ya se guardan en la tabla ventas
                // Ahora manejamos los adicionales mediante tablas pivot

                // Guardar productos adicionales
                if ($request->has('productos')) {
                    foreach ($request->input('productos') as $id => $cantidad) {
                        if ($cantidad > 0) {
                            $venta->productosAdicionales()->attach($id, [
                                'cantidad' => $cantidad,
                                'notas' => $request->input('notas_producto.' . $id, '')
                            ]);
                        }
                    }
                }

                // Guardar paquetes adicionales
                if ($request->has('paquetes')) {
                    foreach ($request->input('paquetes') as $id => $cantidad) {
                        if ($cantidad > 0) {
                            $venta->paquetesAdicionales()->attach($id, [
                                'cantidad' => $cantidad,
                                'notas' => $request->input('notas_paquete.' . $id, '')
                            ]);
                        }
                    }
                }
            }

            // Manejar archivos
            if ($request->hasFile('archivo_propuesta')) {
                $path = $request->file('archivo_propuesta')->store('propuestas', 'public');
                $venta->archivo_propuesta = $path;
                $venta->save();
            }

            if ($request->hasFile('archivo_negociacion')) {
                $path = $request->file('archivo_negociacion')->store('negociaciones', 'public');
                $venta->archivo_negociacion = $path;
                $venta->save();
            }

            if ($request->hasFile('anexo_contrato')) {
                $path = $request->file('anexo_contrato')->store('contratos', 'public');
                $venta->anexo_contrato = $path;
                $venta->save();
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Venta registrada correctamente',
                'venta_id' => $venta->id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Guardar un archivo en el sistema de archivos y devolver su nombre.
     */
    private function guardarArchivo($archivo, $carpeta)
    {
        $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
        $archivo->storeAs($carpeta, $nombreArchivo, 'public');
        return $nombreArchivo;
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
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Verificar si el avance es válido
            if (!$venta->puedeAvanzarA($request->nuevo_estado)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede avanzar a este estado desde el estado actual.'
                ], 400);
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
                    $venta->estado = ($request->estado_final) ?? 'En Curso';

                    if ($request->hasFile('anexo_contrato')) {
                        $archivo = $request->file('anexo_contrato');
                        $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
                        $archivo->storeAs('contratos', $nombreArchivo, 'public');
                        $venta->anexo_contrato = $nombreArchivo;
                    }
                    break;
            }

            $venta->avanzarEstadoComercial($request->nuevo_estado);
            $venta->save();

            return response()->json([
                'success' => true,
                'message' => 'Estado de venta actualizado exitosamente.',
                'venta' => $venta->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el estado: ' . $e->getMessage()
            ], 500);
        }
    }



    // /**
    //  * Store a newly created venta in storage.
    //  */
    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'tipo_venta' => 'required|in:Interna,Externa',
    //         'cliente_id' => 'required|exists:clientes,id',
    //         'empleado_id' => 'required|exists:empleados,id',
    //         'tipo_item_venta' => 'nullable|in:producto,paquete',
    //         'producto_id' => 'nullable|exists:productos,id',
    //         'paquete_id' => 'nullable|exists:paquetes,id',
    //         'detalle_prospeccion' => 'nullable|string',
    //     ]);

    //     if ($validator->fails()) {
    //         return redirect()->route('ventas.index')
    //             ->withErrors($validator)
    //             ->withInput();
    //     }

    //     try {
    //         DB::beginTransaction();

    //         $venta = Venta::create([
    //             'tipo_venta' => $request->tipo_venta,
    //             'cliente_id' => $request->cliente_id,
    //             'empleado_id' => $request->empleado_id,
    //             'estado_comercial' => 'Prospección', // Estado inicial
    //             'estado' => 'Pendiente', // Estado inicial
    //             'tipo_item_venta' => $request->tipo_item_venta,
    //             'producto_id' => $request->producto_id,
    //             'paquete_id' => $request->paquete_id,
    //             'detalle_prospeccion' => $request->detalle_prospeccion,
    //         ]);

    //         // Si hay productos seleccionados
    //         if ($request->productos) {
    //             foreach ($request->productos as $productoId => $cantidad) {
    //                 if ($cantidad > 0) {
    //                     $venta->productos()->attach($productoId, [
    //                         'cantidad' => $cantidad,
    //                         'notas' => $request->notas_producto[$productoId] ?? null
    //                     ]);
    //                 }
    //             }
    //         }

    //         // Si hay paquetes seleccionados
    //         if ($request->paquetes) {
    //             foreach ($request->paquetes as $paqueteId => $cantidad) {
    //                 if ($cantidad > 0) {
    //                     $venta->paquetes()->attach($paqueteId, [
    //                         'cantidad' => $cantidad,
    //                         'notas' => $request->notas_paquete[$paqueteId] ?? null
    //                     ]);
    //                 }
    //             }
    //         }

    //         DB::commit();

    //         return redirect()->route('ventas.index')
    //             ->with('success', 'Venta creada exitosamente.');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return redirect()->route('ventas.index')
    //             ->with('error', 'Error al crear la venta: ' . $e->getMessage());
    //     }
    // }

    // /**
    //  * Display the specified venta.
    //  */
    // public function show(Venta $venta)
    // {
    //     $venta->load(['cliente', 'empleado', 'productos', 'paquetes']);
    //     return view('ventas.show', compact('venta'));
    // }

    // /**
    //  * Show the form for editing the specified venta.
    //  */
    // public function edit(Venta $venta)
    // {
    //     $clientes = Cliente::all();
    //     $empleados = Empleados::all();
    //     $productos = Producto::where('activo', 1)->get();
    //     $paquetes = Paquete::all();

    //     return view('ventas.edit', compact('venta', 'clientes', 'empleados', 'productos', 'paquetes'));
    // }

    // /**
    //  * Update the specified venta in storage.
    //  */
    // public function update(Request $request, Venta $venta)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'tipo_venta' => 'required|in:Interna,Externa',
    //         'cliente_id' => 'required|exists:clientes,id',
    //         'empleado_id' => 'required|exists:empleados,id',
    //         'tipo_item_venta' => 'nullable|in:producto,paquete',
    //         'producto_id' => 'nullable|exists:productos,id',
    //         'paquete_id' => 'nullable|exists:paquetes,id',
    //     ]);

    //     if ($validator->fails()) {
    //         return redirect()->route('ventas.edit', $venta)
    //             ->withErrors($validator)
    //             ->withInput();
    //     }

    //     try {
    //         DB::beginTransaction();

    //         $venta->update($request->all());

    //         // Actualizar productos
    //         if ($request->productos) {
    //             $venta->productos()->detach();
    //             foreach ($request->productos as $productoId => $cantidad) {
    //                 if ($cantidad > 0) {
    //                     $venta->productos()->attach($productoId, [
    //                         'cantidad' => $cantidad,
    //                         'notas' => $request->notas_producto[$productoId] ?? null
    //                     ]);
    //                 }
    //             }
    //         }

    //         // Actualizar paquetes
    //         if ($request->paquetes) {
    //             $venta->paquetes()->detach();
    //             foreach ($request->paquetes as $paqueteId => $cantidad) {
    //                 if ($cantidad > 0) {
    //                     $venta->paquetes()->attach($paqueteId, [
    //                         'cantidad' => $cantidad,
    //                         'notas' => $request->notas_paquete[$paqueteId] ?? null
    //                     ]);
    //                 }
    //             }
    //         }

    //         DB::commit();

    //         return redirect()->route('ventas.index')
    //             ->with('success', 'Venta actualizada exitosamente.');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return redirect()->route('ventas.edit', $venta)
    //             ->with('error', 'Error al actualizar la venta: ' . $e->getMessage());
    //     }
    // }

    // /**
    //  * Remove the specified venta from storage.
    //  */
    // public function destroy(Venta $venta)
    // {
    //     try {
    //         $venta->delete();
    //         return redirect()->route('ventas.index')
    //             ->with('success', 'Venta eliminada exitosamente.');
    //     } catch (\Exception $e) {
    //         return redirect()->route('ventas.index')
    //             ->with('error', 'Error al eliminar la venta: ' . $e->getMessage());
    //     }
    // }

    // /**
    //  * Pausar una venta
    //  */
    // public function pausar(Venta $venta)
    // {
    //     try {
    //         $venta->pausar();
    //         return redirect()->back()->with('success', 'Venta pausada exitosamente.');
    //     } catch (\Exception $e) {
    //         return redirect()->back()->with('error', 'Error al pausar la venta: ' . $e->getMessage());
    //     }
    // }

    // /**
    //  * Reanudar una venta pausada
    //  */
    // public function reanudar(Venta $venta)
    // {
    //     try {
    //         $venta->reanudar();
    //         return redirect()->back()->with('success', 'Venta reanudada exitosamente.');
    //     } catch (\Exception $e) {
    //         return redirect()->back()->with('error', 'Error al reanudar la venta: ' . $e->getMessage());
    //     }
    // }

    // /**
    //  * Avanzar al siguiente estado comercial en el workflow
    //  */
    // public function avanzarEstado(Request $request, Venta $venta)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'nuevo_estado' => 'required|in:Prospección,Contacto,Presentación,Propuesta,Negociación,Cierre',
    //     ]);

    //     if ($validator->fails()) {
    //         return redirect()->back()
    //             ->withErrors($validator)
    //             ->withInput();
    //     }

    //     try {
    //         // Verificar si el avance es válido
    //         if (!$venta->puedeAvanzarA($request->nuevo_estado)) {
    //             return redirect()->back()->with('error', 'No se puede avanzar a este estado desde el estado actual.');
    //         }

    //         // Actualizar los datos específicos del nuevo estado
    //         switch ($request->nuevo_estado) {
    //             case 'Contacto':
    //                 $venta->fecha_contacto = $request->fecha_contacto ?? now();
    //                 $venta->detalle_contacto = $request->detalle_contacto;
    //                 $venta->canal_comunicacion = $request->canal_comunicacion;
    //                 break;
    //             case 'Presentación':
    //                 $venta->fecha_presentacion = $request->fecha_presentacion ?? now();
    //                 $venta->observacion_presentacion = $request->observacion_presentacion;
    //                 break;
    //             case 'Propuesta':
    //                 $venta->fecha_propuesta = $request->fecha_propuesta ?? now();
    //                 $venta->detalle_propuesta = $request->detalle_propuesta;

    //                 // Manejar carga de archivo si existe
    //                 if ($request->hasFile('archivo_propuesta')) {
    //                     $archivo = $request->file('archivo_propuesta');
    //                     $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
    //                     $archivo->storeAs('propuestas', $nombreArchivo, 'public');
    //                     $venta->archivo_propuesta = $nombreArchivo;
    //                 }
    //                 break;
    //             case 'Negociación':
    //                 $venta->fecha_negociacion = $request->fecha_negociacion ?? now();
    //                 $venta->detalle_negociacion = $request->detalle_negociacion;

    //                 // Manejar carga de archivo si existe
    //                 if ($request->hasFile('archivo_negociacion')) {
    //                     $archivo = $request->file('archivo_negociacion');
    //                     $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
    //                     $archivo->storeAs('negociaciones', $nombreArchivo, 'public');
    //                     $venta->archivo_negociacion = $nombreArchivo;
    //                 }
    //                 break;
    //             case 'Cierre':
    //                 $venta->fecha_venta = $request->fecha_venta ?? now();
    //                 $venta->fecha_contrato = $request->fecha_contrato;
    //                 $venta->fecha_cobro = $request->fecha_cobro;
    //                 $venta->fecha_expiracion = $request->fecha_expiracion;

    //                 // Cambiar estado general a "En Curso" o "Finalizada" según corresponda
    //                 $venta->estado = ($request->estado_final) ?? 'En Curso';

    //                 // Manejar carga de archivo si existe
    //                 if ($request->hasFile('anexo_contrato')) {
    //                     $archivo = $request->file('anexo_contrato');
    //                     $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
    //                     $archivo->storeAs('contratos', $nombreArchivo, 'public');
    //                     $venta->anexo_contrato = $nombreArchivo;
    //                 }
    //                 break;
    //         }

    //         // Avanzar el estado comercial
    //         $venta->avanzarEstadoComercial($request->nuevo_estado);
    //         $venta->save();

    //         return redirect()->back()->with('success', 'Estado de venta actualizado exitosamente.');
    //     } catch (\Exception $e) {
    //         return redirect()->back()->with('error', 'Error al actualizar el estado: ' . $e->getMessage());
    //     }
    // }
}
