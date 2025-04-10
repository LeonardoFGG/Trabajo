<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rubro;

class RubroSeeder extends Seeder
{
    public function run()
    {
        // Crear algunos rubros de ejemplo
        Rubro::create([
            'nombre' => 'Sueldo Unificado',
            'descripcion' => '',
            'tipo_rubro' => 'ingreso',
        ]);

        Rubro::create([
            'nombre' => 'Viaticos',
            'descripcion' => '',
            'tipo_rubro' => 'ingreso',
        ]);

        Rubro::create([
            'nombre' => 'Decimo Tercero',
            'descripcion' => '',
            'tipo_rubro' => 'ingreso',
        ]);

        Rubro::create([
            'nombre' => 'Decimo Cuarto',
            'descripcion' => '',
            'tipo_rubro' => 'ingreso',
        ]);

        Rubro::create([
            'nombre' => 'Fondo de Reserva',
            'descripcion' => '',
            'tipo_rubro' => 'ingreso',
        ]);

        Rubro::create([
            'nombre' => 'Bonificaciones',
            'descripcion' => '',
            'tipo_rubro' => 'ingreso',
        ]);

        Rubro::create([
            'nombre' => 'IESS',
            'descripcion' => '',
            'tipo_rubro' => 'egreso',
        ]);

        Rubro::create([
            'nombre' => 'Prestamos IESS',
            'descripcion' => '',
            'tipo_rubro' => 'egreso',
        ]);

        Rubro::create([
            'nombre' => 'Cajita de Ahorro Quincenal',
            'descripcion' => '',
            'tipo_rubro' => 'egreso',
        ]);

        Rubro::create([
            'nombre' => 'Cajita de Ahorro Mensual',
            'descripcion' => '',
            'tipo_rubro' => 'egreso',
        ]);

        Rubro::create([
            'nombre' => 'Prestamos',
            'descripcion' => '',
            'tipo_rubro' => 'egreso',
        ]);

        Rubro::create([
            'nombre' => 'Quincena',
            'descripcion' => '',
            'tipo_rubro' => 'ingreso',
        ]);

        Rubro::create([
            'nombre' => 'Impuestos a la Rentas',
            'descripcion' => '',
            'tipo_rubro' => 'egreso',
        ]);
    }
}
