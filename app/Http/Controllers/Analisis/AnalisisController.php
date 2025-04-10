<?php

namespace App\Http\Controllers\Analisis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AnalisisController extends Controller
{
    public function index()
    {
        // Supongamos que quieres cargar una vista vacía llamada "indexAnalisis"
        return view('Analisis.indexAnalisis');
    }

    public function indexGestion()
    {
        // Supongamos que quieres cargar una vista vacía llamada "indexGestion"
        return view('Analisis.indexGestion');
    }

    public function indexActividades()
    {
        // Supongamos que quieres cargar una vista vacía llamada "indexActividades"
        return view('Analisis.indexActividades');
    }

    public function indexMatriz()
    {
        // Supongamos que quieres cargar una vista vacía llamada "indexMatriz"
        return view('Analisis.indexMatriz');
    }

    public function indexSatisfaccion()
    {
        // Supongamos que quieres cargar una vista vacía llamada "indexSatisfaccion"
        return view('Analisis.indexSatisfaccion');
    }

    public function indexExterno()
    {
        // Supongamos que quieres cargar una vista vacía llamada "indexExterno"
        return view('Analisis.indexExterno');
    }


}