<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $table = 'ventas';

    protected $fillable = [
        'tipo_venta',
        'cliente_id',
        'empleado_id',
        'estado_comercial',
        'estado',
        'tipo_item_venta',
        'producto_id',
        'paquete_id',
        'detalle_prospeccion',
        'fecha_contacto',
        'detalle_contacto',
        'canal_comunicacion',
        'fecha_presentacion',
        'observacion_presentacion',
        'fecha_propuesta',
        'archivo_propuesta',
        'detalle_propuesta',
        'fecha_negociacion',
        'archivo_negociacion',
        'detalle_negociacion',
        'fecha_venta',
        'fecha_contrato',
        'fecha_cobro',
        'fecha_expiracion',
        'anexo_contrato',
    ];

    protected $dates = [
        'fecha_contacto',
        'fecha_presentacion',
        'fecha_propuesta',
        'fecha_negociacion',
        'fecha_venta',
        'fecha_contrato',
        'fecha_cobro',
        'fecha_expiracion',
        'created_at',
        'updated_at',
    ];

    // Relaciones
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function empleado()
    {
        return $this->belongsTo(Empleados::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function paquete()
    {
        return $this->belongsTo(Paquete::class);
    }

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'producto_venta')
            ->withPivot('cantidad', 'notas')
            ->withTimestamps();
    }

    public function paquetes()
    {
        return $this->belongsToMany(Paquete::class, 'paquete_venta')
            ->withPivot('cantidad', 'notas')
            ->withTimestamps();
    }

    // Scopes para filtrar ventas
    public function scopeActivas($query)
    {
        return $query->where('estado', 'Activa');
    }

    public function scopePendientes($query)
    {
        return $query->where('estado', 'Pendiente');
    }

    public function scopePausadas($query)
    {
        return $query->where('estado', 'Pausada');
    }

    public function scopeFinalizadas($query)
    {
        return $query->where('estado', 'Finalizada');
    }

    // Métodos para el workflow
    public function pausar()
    {
        $this->estado = 'Pausada';
        $this->save();
        
        return $this;
    }

    public function reanudar()
    {
        $this->estado = 'Activa';
        $this->save();
        
        return $this;
    }

    public function avanzarEstadoComercial($nuevoEstado)
    {
        $estadosValidos = [
            'Prospección', 
            'Contacto',
            'Presentación', 
            'Propuesta',
            'Negociación', 
            'Cierre'
        ];

        if (in_array($nuevoEstado, $estadosValidos)) {
            $this->estado_comercial = $nuevoEstado;
            $this->save();
        }

        return $this;
    }

    public function cambiarEstado($nuevoEstado)
    {
        $estadosValidos = [
            'Pendiente', 
            'Activa', 
            'Inactiva', 
            'Expirada', 
            'Cancelada', 
            'En Curso', 
            'Finalizada', 
            'Pausada'
        ];

        if (in_array($nuevoEstado, $estadosValidos)) {
            $this->estado = $nuevoEstado;
            $this->save();
        }

        return $this;
    }

    // Helper para verificar si se puede avanzar al siguiente estado
    public function puedeAvanzarA($estadoDestino)
    {
        $flujoEstados = [
            'Prospección' => ['Contacto'],
            'Contacto' => ['Presentación'],
            'Presentación' => ['Propuesta'],
            'Propuesta' => ['Negociación'],
            'Negociación' => ['Cierre'],
            'Cierre' => []
        ];

        return in_array($estadoDestino, $flujoEstados[$this->estado_comercial] ?? []);
    }
}