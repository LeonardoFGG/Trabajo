<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Departamento;
use App\Models\Supervisor;

class DepartamentoSeeder extends Seeder
{
    public function run()
    {
        Departamento::create([
            'nombre' => 'ÁREA INFRAESTRUCTURA TI',
            'descripcion' => 'Encargada de gestionar y mantener la infraestructura tecnológica de la organización para asegurar su funcionamiento óptimo.',
            
        ]);

        Departamento::create([
            'nombre' => 'ÁREA ATECIÓN AL CLIENTE',
            'descripcion' => 'Focalizada en brindar un servicio excepcional a los clientes, resolviendo sus inquietudes y mejorando su experiencia.',
            
        ]);

        Departamento::create([
            'nombre' => 'ÁREA TICS',
            'descripcion' => 'Se ocupa de la implementación y gestión de tecnologías de la información y comunicación para optimizar procesos internos.',
        ]);

        Departamento::create([
            'nombre' => 'ÁREA SEGURIDAD DE LA INFORMACIÓN Y ANÁLISIS DE DATOS',
            'descripcion' => 'Responsable de proteger la información sensible y analizar datos para la toma de decisiones estratégicas.',
        ]);

        Departamento::create([
            'nombre' => 'ÁREA FINANCIERA',
            'descripcion' => 'Gestiona los recursos financieros, realizando análisis económicos y asegurando la sostenibilidad financiera de la organización.',
        ]);

        Departamento::create([
            'nombre' => 'ÁREA COMERCIAL',
            'descripcion' => 'Encargada de desarrollar y ejecutar estrategias de ventas para maximizar los ingresos y expandir la presencia en el mercado.',
        ]);

        Departamento::create([
            'nombre' => 'ÁREA AUDITORÍA',
            'descripcion' => 'Responsable de evaluar y mejorar los procesos internos, asegurando el cumplimiento normativo y la transparencia en la gestión.',
        ]);
	 Departamento::create([
            'nombre' => 'ADMINISTRATIVO',
            'descripcion' => 'Gestiona los recursos humanos y materiales, optimizando procesos y coordinando actividades para alcanzar los objetivos de la organización.',
        ]);

     

           
    }
}
