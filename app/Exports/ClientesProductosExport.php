<?php

namespace App\Exports;

use App\Models\Cliente;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ClientesProductosExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function collection()
    {
        return Cliente::where('estado', 'ACTIVO')
            ->with(['productos', 'preciosEspeciales'])
            ->orderBy('nombre')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID Cliente',
            'Nombre Cliente',
            'Contacto',
            'Teléfono',
            'Email',
            'ID Producto',
            'Producto',
            'Categoría',
            'Precio Base',
            'Precio Especial',
            'Diferencia',
            '% Variación',
            'Estado Producto'
        ];
    }

    public function map($cliente): array
    {
        $result = [];
        
        foreach ($cliente->productos as $producto) {
            $precioBase = $producto->valor_producto;
            $precioEspecial = $cliente->preciosEspeciales
                ->where('producto_id', $producto->id)
                ->first();
            
            $diferencia = $precioBase - ($precioEspecial ? $precioEspecial->precio : $precioBase);
            $porcentaje = $precioBase != 0 ? ($diferencia / $precioBase) * 100 : 0;
            
            $result[] = [
                $cliente->id,
                $cliente->nombre,
                $cliente->contacto ?: 'N/A',
                $cliente->telefono ?: 'N/A',
                $cliente->email ?: 'N/A',
                $producto->id,
                $producto->nombre,
                $producto->categoria,
                '$' . number_format($precioBase, 2),
                $precioEspecial ? '$' . number_format($precioEspecial->precio, 2) : '= Base',
                '$' . number_format($diferencia, 2),
                number_format($porcentaje, 2) . '%',
                $producto->activo ? 'ACTIVO' : 'INACTIVO'
            ];
        }
        
        return $result;
    }
}