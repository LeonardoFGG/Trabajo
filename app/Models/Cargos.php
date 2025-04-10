<?php 

/*****************************************************
 * Nombre del Proyecto: ERP 
 * Modulo: Cargos
 * Version: 1.0
 * Desarrollado por: Karol Macas
 * Fecha de Inicio: 
 * Ultima Modificación: 
 ****************************************************/

 namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;



    class Cargos extends Model
    {
        use HasFactory;

        protected $fillable = [
            'nombre_cargo',
            'descripcion',
            'codigo_afiliacion',
            'salario_basico',
            'departamento_id'
        ];

        public function departamento()
        {
            return $this->belongsTo(Departamento::class, 'departamento_id');
        }

        
    }





