<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rubro extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'tipo_rubro'
    ];

    /**
     * Define el tipo de rubro como 'ingreso' o 'egreso'
     */
    protected $casts = [
        'tipo_rubro' => 'string',
    ];

    public function rolesPago()
    {
        return $this->belongsToMany(RolPago::class, 'empleado_rubro', 'rubro_id', 'empleado_id');  
    }

    public function empleados()
    {
        return $this->belongsToMany(Empleados::class, 'empleado_rubro', 'rubro_id', 'empleado_id')
        ->withPivot('monto');
    }
}
