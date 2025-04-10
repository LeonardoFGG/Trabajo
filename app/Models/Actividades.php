<?php
/*****************************************************
 * Nombre del Proyecto: ERP 
 * Modulo: Actividades
 * Version: 1.0
 * Desarrollado por: Karol Macas
 * Fecha de Inicio: 
 * Ultima Modificación: 
 ****************************************************/

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Actividades extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',  // Cooperativa
        'empleado_id',  // ID de empleado
        'producto_id',  // ID de producto
        'descripcion',  // Actividades
        'codigo_osticket',  // CODIGO OSTICKET
        'semanal_diaria',  // Solo permite "SEMANAL" o "DIARIO"
        'fecha_inicio',  // Fecha inicio
        'avance',  // Avance como porcentaje entre 0 y 100
        'observaciones',  // Comentario (opcional)
        'estado',  // Estado
        'tiempo_estimado',
        'tiempo_real_horas',  // Tiempo real en horas
        'tiempo_real_minutos',  // Tiempo real en minutos
        'tiempo_acumulado_minutos', // Tiempo acumulado en minutos
        'fecha_fin',  // Fecha final
        'repetitivo',  // Repetitivo (NO/SÍ)
        'prioridad',  // Prioridad
        'departamento_id',  // ID de departamento
        'cargo_id',  // ID de cargo
        'error',  // Error
    ];

    protected $dates = ['fecha_inicio', 'fecha_fin']; // Asegúrate de incluir esto

    public function empleado()
    {
        return $this->belongsTo(Empleados::class, 'empleado_id');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'departamento_id');
    }

    public function cargo()
    {
        return $this->belongsTo(Cargos::class, 'cargo_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    
}
