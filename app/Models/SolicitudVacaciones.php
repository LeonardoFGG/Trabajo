<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudVacaciones extends Model
{
    use HasFactory;

    protected $table = 'solicitud_vacaciones'; // Nombre de la tabla en la base de datos

    protected $fillable = [
        'empleado_id',
        'fecha_solicitud',
        'fecha_inicio',
        'fecha_fin',
        'dias_solicitados',
        'estado',
        'comentarios',
        'aprobado_por',
    ];

    // Relación con el modelo Empleados
    public function empleado()
    {
        return $this->belongsTo(Empleados::class, 'empleado_id');
    }

    public function aprobadoPor()
    {
        return $this->belongsTo(User::class, 'aprobado_por');
    }
}