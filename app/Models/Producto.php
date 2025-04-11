<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'version',
        'codigo',
        'descripcion',
        'tipo',
        'categoria',
        'valor_producto',
        'incluido_en_paquete',
        'periodicidad_cobro',
        'activo',
        'producto_padre_id',
        'modalidad_servicio',
    ];

    protected $casts = [
        'incluido_en_paquete' => 'boolean',
        'activo' => 'boolean',
        'valor_producto' => 'decimal:2'
    ];

    // Definición de relaciones
    // Relación consigo mismo para productos padre/hijo
    public function productoPadre()
    {
        return $this->belongsTo(Producto::class, 'producto_padre_id');
    }
    // Relación con modulos y servicios
    public function modulos()
    {
        return $this->hasMany(Producto::class, 'producto_padre_id')->where('tipo', 'modulo');
    }

    public function servicios()
    {
        return $this->hasMany(Producto::class, 'producto_padre_id')->where('tipo', 'servicio');
    }

    public function sistemas()
    {
        return $this->hasMany(Producto::class, 'producto_padre_id')->where('tipo', 'sistemas');
    }

    public function aplicacion()
    {
        return $this->hasMany(Producto::class, 'producto_padre_id')->where('tipo', 'aplicacion');
    }

    public function proceso()
    {
        return $this->hasMany(Producto::class, 'producto_padre_id')->where('tipo', 'proceso');
    }

    // Relación con paquetes (si implementas esa funcionalidad)
    public function paquetes()
    {
        return $this->belongsToMany(Paquete::class, 'paquete_producto');
    }

    public function paquetesComoPrincipal()
    {
        return $this->hasMany(Paquete::class, 'sistema_id');
    }

    // Relación con clientes (si implementas esa funcionalidad)
    public function clientes()
    {
        return $this->belongsToMany(Cliente::class, 'cliente_producto')
            ->withPivot('producto_id', 'cliente_id')
            ->withTimestamps();
    }

    // Scope para productos activos
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    // Scope para productos de un tipo específico
    public function scopeDeTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

}
