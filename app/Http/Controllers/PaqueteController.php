<?php

namespace App\Http\Controllers;

use App\Models\Paquete;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaqueteController extends Controller
{
    /**
     * Muestra la vista de todos los paquetes
     */
    public function index()
    {
        $paquetes = Paquete::with(['sistema', 'productos'])->get();
        $sistemas = Producto::where('tipo', 'core')
                    ->where('activo', true)
                    ->orderBy('nombre')
                    ->get();
        $productos = Producto::where('activo', true)
                    ->orderBy('tipo')
                    ->orderBy('nombre')
                    ->get();
        $categorias = Producto::select('categoria')
                    ->whereNotNull('categoria')
                    ->distinct()
                    ->pluck('categoria')
                    ->filter()
                    ->values();
        $tipos = ['core', 'modulo', 'servicio', 'estructura', 'implementacion'];
        
        return view('paquetes.index', compact('paquetes', 'sistemas', 'productos', 'categorias', 'tipos'));
    }

    /**
     * Muestra el formulario para crear un nuevo paquete
     */
    public function create()
    {
        $sistemas = Producto::where('tipo', 'core')
                    ->where('activo', true)
                    ->orderBy('nombre')
                    ->get();
                    
        $productos = Producto::where('activo', true)
                    ->orderBy('tipo')
                    ->orderBy('nombre')
                    ->get();
        
        return view('paquetes.create', compact('sistemas', 'productos'));
    }

    /**
     * Almacena un nuevo paquete en la base de datos
     */
    public function store(Request $request)
    {

        $request->merge([
            'activo' => $request->has('activo'),
        ]);

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:50',
            'descripcion' => 'nullable|string',
            'precio_base' => 'required|numeric',
            'activo' => 'boolean',
            'sistema_id' => 'nullable|exists:productos,id',
            'productos' => 'array',
            'productos.*' => 'exists:productos,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        $paquete = Paquete::create([
            'nombre' => $request->nombre,
            'codigo' => $request->codigo,
            'descripcion' => $request->descripcion,
            'precio_base' => $request->precio_base,
            'activo' => $request->activo,
            'sistema_id' => $request->sistema_id,
        ]);

        // Sincronizar productos del paquete
        if ($request->has('productos')) {
            $paquete->productos()->sync($request->productos);
        }

        return redirect()->route('paquetes.index')
            ->with('success', 'Paquete creado exitosamente');

    }


        


    /**
     * Muestra un paquete específico
     */
    public function show(Paquete $paquete)
    {
        $paquete->load(['sistema', 'productos']);
        
        return view('paquetes.show', compact('paquete'));
    }

    /**
     * Muestra el formulario para editar un paquete
     */
    public function edit(Paquete $paquete)
    {
        $sistemas = Producto::where('tipo', 'core')
                    ->where('activo', true)
                    ->orderBy('nombre')
                    ->get();
                    
        $productos = Producto::where('activo', true)
                    ->orderBy('tipo')
                    ->orderBy('nombre')
                    ->get();
        
        $paquete->load('productos');
        $productosSeleccionados = $paquete->productos->pluck('id')->toArray();
        
        return view('paquetes.edit', compact('paquete', 'sistemas', 'productos', 'productosSeleccionados'));
    }

    /**
     * Actualiza un paquete en la base de datos
     */
    public function update(Request $request, Paquete $paquete)
    {
        $request->merge([
            'activo' => $request->has('activo'),
        ]);

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:50',
            'descripcion' => 'nullable|string',
            'precio_base' => 'required|numeric',
            'activo' => 'boolean',
            'sistema_id' => 'nullable|exists:productos,id',
            'productos' => 'array',
            'productos.*' => 'exists:productos,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        $paquete->update([
            'nombre' => $request->nombre,
            'codigo' => $request->codigo,
            'descripcion' => $request->descripcion,
            'precio_base' => $request->precio_base,
            'activo' => $request->activo,
            'sistema_id' => $request->sistema_id,
        ]);

        // Sincronizar productos del paquete
        if ($request->has('productos')) {
            $paquete->productos()->sync($request->productos);
        } else {
            $paquete->productos()->detach();
        }

        return redirect()->route('paquetes.index')
            ->with('success', 'Paquete actualizado exitosamente');
    }
    
    /**
     * Elimina un paquete de la base de datos
     */
    public function destroy(Paquete $paquete)
    {
        // Verificar si el paquete está siendo usado en ventas
        if ($paquete->ventas()->count() > 0) {
            return redirect()->route('paquetes.index')
                ->with('error', 'No se puede eliminar el paquete porque está asociado a ventas');
        }

        // Eliminar relaciones con productos
        $paquete->productos()->detach();
        
        $paquete->delete();

        return redirect()->route('paquetes.index')
            ->with('success', 'Paquete eliminado exitosamente');
    }
}