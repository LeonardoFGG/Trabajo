<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Daily extends Model
{
    use HasFactory;

    protected $fillable = ['empleado_id', 'fecha', 'ayer', 'hoy', 'dificultades'];
    protected $dates = ['fecha'];

    /**
     * Define la relación con el modelo Empleado.
     */
    public function empleado()
    {
        return $this->belongsTo(Empleados::class, 'empleado_id');
    }
}
