<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Producto;
use App\Models\Venta;

class Paquete extends Model
{
    protected $fillable = [
        'nombre',
        'codigo',
        'descripcion',
        'precio_base',
        'activo',
        'sistema_id'
    ];

    protected $casts = [
        'precio_base' => 'decimal:2',
        'activo' => 'boolean'
    ];

    // Relación con el sistema principal
    public function sistema(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'sistema_id');
    }

    // Relación con productos incluidos
    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'paquete_producto');
    }
   
    // Calcular precio con descuento
    public function precioConDescuento($porcentaje)
    {
        return $this->precio_base * (1 - ($porcentaje / 100));
    }
}