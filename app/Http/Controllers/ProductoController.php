<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Paquete;
use App\Models\ServiciosTecnicos;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $query = Producto::query();

        // Filtros
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', '%' . $search . '%')
                    ->orWhere('codigo', 'like', '%' . $search . '%')
                    ->orWhere('descripcion', 'like', '%' . $search . '%');
            });
        }

        // Si es petición AJAX, devolver JSON
        if ($request->ajax()) {
            return response()->json([
                'productos' => $query->get(),
                'sistemas' => Producto::where('tipo', 'core')->get(),
                'tipos' => ['core', 'modulo', 'servicio', 'estructura', 'proceso', 'aplicaciones'],
                'periodicidades' => ['diario', 'mensual', 'anual']
            ]);
        }

        // Obtener categorías únicas para el filtro
        $categorias = Producto::select('categoria')
            ->whereNotNull('categoria')
            ->distinct()
            ->pluck('categoria')
            ->filter()
            ->values();

        // Ordenamiento y paginación
        $productos = $query->orderBy('nombre')->get();

        return view('Productos.index', [
            'productos' => $productos,
            'tipos' => ['core', 'modulo', 'servicio', 'estructura', 'proceso', 'aplicaciones'],
            'categorias' => $categorias,
            'sistemas' => Producto::where('tipo', 'core')->get(),

        ]);
    }

    public function create()
    {
        $sistemas = Producto::where('tipo', 'core')->get();
        return view('productos.create', compact('sistemas'));
    }

    public function store(Request $request)
    {
        // Convertir checkboxes a booleanos
        $request->merge([
            'activo' => $request->has('activo'),
            'incluido_en_paquete' => $request->has('incluido_en_paquete')
        ]);

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'version' => 'nullable|string|max:20',
            'codigo' => 'nullable|string|max:50|unique:productos,codigo',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:core,modulo,servicio,estructura,proceso,aplicaciones',
            'categoria' => 'nullable|string|max:100',
            'valor_producto' => 'nullable|numeric|min:0',
            'incluido_en_paquete' => 'boolean',
            'periodicidad_cobro' => 'required|in:diario,mensual,anual',
            'producto_padre_id' => 'nullable|exists:productos,id',
            'modalidad_servicio' => 'nullable|in:remoto,presencial',
            'activo' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $producto = Producto::create([
            'nombre' => $request->nombre,
            'version' => $request->version,
            'codigo' => $request->codigo,
            'descripcion' => $request->descripcion ?? '',
            'tipo' => $request->tipo,
            'categoria' => $request->categoria,
            'valor_producto' => $request->valor_producto,
            'incluido_en_paquete' => $request->incluido_en_paquete,
            'periodicidad_cobro' => $request->periodicidad_cobro,
            'activo' => $request->activo,
            'modalidad_servicio' => $request->modalidad_servicio,
            'producto_padre_id' => $request->producto_padre_id ? $request->producto_padre_id : null
        ]);

        return redirect()->route('productos.index')
            ->with('success', 'Producto creado exitosamente.');
    }

    public function show($id)
    {
        $producto = Producto::with(['productoPadre', 'modulos', 'servicios'])->findOrFail($id);
        return view('productos.show', compact('producto'));
    }

    public function edit($id)
    {
        $producto = Producto::findOrFail($id);
        $sistemas = Producto::where('tipo', 'core')->get();
        return view('productos.edit', compact('producto', 'sistemas'));
    }

    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        $request->merge([
            'activo' => $request->has('activo'),
            'incluido_en_paquete' => $request->has('incluido_en_paquete')
        ]);

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'version' => 'nullable|string|max:20',
            'codigo' => 'nullable|string|max:50|unique:productos,codigo,' . $id,
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:core,modulo,servicio,estructura,implementacion,aplicaciones',
            'categoria' => 'nullable|string|max:100',
            'valor_producto' => 'nullable|numeric|min:0',
            'incluido_en_paquete' => 'boolean',
            'periodicidad_cobro' => 'required|in:diario,mensual,anual',
            'producto_padre_id' => 'nullable|exists:productos,id',
            'modalidad_servicio' => 'nullable|in:remoto,presencial',
            'activo' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        $data = [
            'nombre' => $request->nombre,
            'version' => $request->version,
            'codigo' => $request->codigo,
            'descripcion' => $request->descripcion ?? '',
            'tipo' => $request->tipo,
            'categoria' => $request->categoria,
            'valor_producto' => $request->valor_producto,
            'incluido_en_paquete' => $request->incluido_en_paquete,
            'periodicidad_cobro' => $request->periodicidad_cobro,
            'modalidad_servicio' => $request->modalidad_servicio,
            'activo' => $request->activo
        ];

        // Solo actualizar producto_padre_id si es módulo o servicio
        if (in_array($request->tipo, ['modulo', 'servicio'])) {
            $data['producto_padre_id'] = $request->producto_padre_id;
        } else {
            $data['producto_padre_id'] = null;
        }

        $producto->update($data);

        return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);

        // Verificar si el producto está siendo usado en algún paquete o cliente
        if ($producto->paquetes()->exists() || $producto->clientes()->exists()) {
            return redirect()->route('productos.index')
                ->with('error', 'No se puede eliminar el producto porque está asociado a paquetes o clientes.');
        }

        $producto->delete();

        return redirect()->route('productos.index')
            ->with('success', 'Producto eliminado exitosamente.');
    }

    public function toggleStatus(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);
        $producto->activo = !$producto->activo;
        $producto->save();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'activo' => $producto->activo]);
        }

        return redirect()->back()
            ->with('success', 'Estado del producto actualizado.');
    }

    public function getByType($type)
    {
        $productos = Producto::where('tipo', $type)
            ->where('activo', true)
            ->orderBy('nombre')
            ->get();

        return response()->json($productos);
    }

    public function getBySystem($systemId)
    {
        $modulos = Producto::where('producto_padre_id', $systemId)
            ->where('tipo', 'modulo')
            ->where('activo', true)
            ->orderBy('nombre')
            ->get();

        return response()->json($modulos);
    }
}
