<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    use HasFactory;

    protected $table = 'permisos';

    protected $fillable = [
        'empleado_id',
        'fecha_solicitud',
        'fecha_salida',
        'hora_salida',
        'hora_regreso',
        'duracion',
        'tipo_permiso',
        'anexos',
        'motivo',
        'estado',
        'justificado',
        'aprobado_por',

    ];

    public function empleado()
    {
        return $this->belongsTo(Empleados::class, 'empleado_id');
    }

    public function supervisor()
    {
        return $this->belongsTo(Empleados::class, 'supervisor_id');
    }

    public function aprobadoPor()
    {
        return $this->belongsTo(User::class, 'aprobado_por');
    }
}
