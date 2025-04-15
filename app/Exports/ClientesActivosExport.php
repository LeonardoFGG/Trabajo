<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Models\Cliente;

class ClientesActivosExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Cliente::with('productos')
            ->where('estado', 'ACTIVO')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'Dirección',
            'Teléfono',
            'Email',
            'Contacto',
            'Productos',
            'Valor Productos',
            'Estado'
        ];
    }

    public function map($cliente): array
    {
        return [
            $cliente->id,
            $cliente->nombre,
            $cliente->direccion,
            $cliente->telefono,
            $cliente->email,
            $cliente->contacto,
            $cliente->productos->pluck('nombre')->implode(', '),
            '$' . number_format($cliente->total_valor_productos, 2),
            $cliente->estado
        ];
    }
}