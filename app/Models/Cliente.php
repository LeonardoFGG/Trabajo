<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'email',
        'contacto',
        'contrato_implementacion',
        'convenio_datos',
        'documento_otros',
        'total_valor_productos',
        'estado'
    ];

    public function actividades()
    {
        return $this->hasMany(Actividades::class);
    }

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'cliente_producto');
    }

    public function preciosEspeciales()
    {
        return $this->hasMany(PrecioCliente::class);
    }
}
