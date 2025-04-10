<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'descripcion','valor_producto'];

    public function clientes()
    {
        return $this->belongsToMany(Cliente::class, 'cliente_producto');
    }
}
