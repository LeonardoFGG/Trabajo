<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class ActividadesExport implements FromCollection, WithHeadings
{
    protected $actividades;

    public function __construct(Collection $actividades)
    {
        $this->actividades = $actividades;
    }

    public function collection()
    {
        return $this->actividades->map(function ($actividad) {
            return [
                'ID' => $actividad->id,
                'Cliente' => $actividad->cliente->nombre ?? 'N/A',
                'Empleado' => $actividad->empleado ? $actividad->empleado->nombre1.' '.$actividad->empleado->apellido1 : 'N/A',
                'Producto' => $actividad->producto->nombre ?? 'N/A',
                'Descripción' => $actividad->descripcion,
                'Estado' => $actividad->estado,
                'Fecha Inicio' => $actividad->fecha_inicio->format('d/m/Y H:i'),
                'Fecha Fin' => $actividad->fecha_fin ? $actividad->fecha_fin->format('d/m/Y H:i') : 'N/A',
                'Tiempo Real' => ($actividad->tiempo_real_horas ?? 0).'h '.($actividad->tiempo_real_minutos ?? 0).'m',
                'Prioridad' => $actividad->prioridad,
                'Observaciones' => $actividad->observaciones
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Cliente',
            'Empleado',
            'Producto',
            'Descripción',
            'Estado',
            'Fecha Inicio',
            'Fecha Fin',
            'Tiempo Real',
            'Prioridad',
            'Observaciones'
        ];
    }
}