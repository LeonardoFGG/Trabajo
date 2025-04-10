<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Supervisor extends Model
{
    use HasFactory;

    protected $table = 'supervisores'; // Nombre de la tabla en la base de datos 

    protected $fillable = ['empleado_id', 'nombre_supervisor', 'departamento_id', 'es_supervisor_superior',];

    

    public function departamento()
{
    return $this->belongsTo(Departamento::class);
}

    public function empleados()
    {
        return $this->hasMany(Empleados::class, 'supervisor_id');
    }
	public function supervisor()
{
    return $this->belongsTo(Supervisor::class, 'supervisor_id');
}
    
}
