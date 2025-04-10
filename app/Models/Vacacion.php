<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacacion extends Model
{
    use HasFactory;

    protected $table = 'vacaciones';

    protected $fillable = [
        'empleado_id',
        'fecha_ingreso',
        'periodo',
        'desde',
        'hasta',
        'dias_tomados',
        'saldo_vacaciones',
    ];

    protected $casts = [
        'fecha_ingreso' => 'date',
        'desde' => 'date',
        'hasta' => 'date',
    ];

    // Relación con el modelo Empleado
    public function empleado()
    {
        return $this->belongsTo(Empleados::class, 'empleado_id'); // Especifica la clave foránea
    }
    
}