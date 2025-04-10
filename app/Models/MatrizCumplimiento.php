<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatrizCumplimiento extends Model
{
    use HasFactory;

    protected $fillable = ['parametro_id', 'puntos', 'empleado_id', 'cargo_id', 'supervisor_id'];

    public function empleado()
    {
        return $this->belongsTo(Empleados::class);
    }

    public function cargo()
    {
        return $this->belongsTo(Cargos::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class);
    }

    public function parametro()
    {
        return $this->belongsTo(Parametro::class);
    }
}
