<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolPago extends Model
{
    use HasFactory;

    protected $fillable = [
        'empleado_id',
        'fecha_inicio',
        'fecha_fin',
        'total_ingreso',
        'total_egreso',
        'salario_neto'
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleados::class);
    }

    public function rubros()
    {
        return $this->belongsToMany(Rubro::class, 'empleado_rubro', 'empleado_id', 'rubro_id');  
    }

    protected $table = 'roles_pago';

    public function calcularTotales()
    {
        // Obtener todos los rubros asociados al empleado para este rol de pago
        $rubrosIngreso = $this->empleado->rubros()->where('tipo_rubro', 'ingreso')->get();
        $rubrosEgreso = $this->empleado->rubros()->where('tipo_rubro', 'egreso')->get();

        $totalIngreso = $rubrosIngreso->sum('pivot.monto');
        $totalEgreso = $rubrosEgreso->sum('pivot.monto');

        $this->total_ingreso = $totalIngreso;
        $this->total_egreso = $totalEgreso;
        $this->salario_neto = $totalIngreso - $totalEgreso;
        $this->save();
    }
}
