<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Empleados;
use App\Models\Cargos;
use App\Models\Supervisor;

class Departamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'supervisor_id',
        
    ];

    

    public function empleados()
    {
        return $this->hasMany(Empleados::class);
    }

    public function supervisor()
{
    return $this->belongsTo(Supervisor::class, 'supervisor_id');
}

    public function cargos()
    {
        return $this->hasMany(Cargos::class);
    }

    public function parametros()
    {
        return $this->hasMany(Parametro::class);
    }

}
