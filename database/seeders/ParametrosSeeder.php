<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Parametro;

class ParametrosSeeder extends Seeder
{
    public function run()
    {
        $parametros = [
            [
                'nombre' => 'No Participar Eventos Scrum',
                'departamento_id' => 2,
            ],
            [
                'nombre' => 'No Registrar Actividades Diarias ERP',
                'departamento_id' => 2,
            ],
            [
                'nombre' => 'Mala Atencion al Cliente',
                'departamento_id' => 2,
            ],
            [
                'nombre' => 'No Crear Instructivos Soluciones',
                'departamento_id' => 2,
            ],
            [
                'nombre' => 'No Crear Manuales de Usuario',
                'departamento_id' => 2,
            ],
            [
                'nombre' => 'Soportes Sin Ticket',
                'departamento_id' => 2,
            ],
            [
                'nombre' => 'No Identificar Errores Operativos',
                'departamento_id' => 2,
            ],
            [
                'nombre' => 'Enviar Ticket a Tics Sin Revisión',
                'departamento_id' => 2,
            ],
            [
                'nombre' => 'No Documentar Errores',
                'departamento_id' => 2,
            ],
            [
                'nombre' => 'No Cumplir Procesos De Capacitación',
                'departamento_id' => 2,
            ],
            [
                'nombre' => 'No Cumplir con los Errores de Documentación PAG,DPF,Apertura,Contratos',
                'departamento_id' => 2,
            ],
            [
                'nombre' => 'Mala Parametrización',
                'departamento_id' => 2,
            ],
            [
                'nombre' => 'Retrazos de Fecha de Entrega de Productos',
                'departamento_id' => 2,
            ],
            [
                'nombre' => 'Retrazo de Soportes',
                'departamento_id' => 2,
            ],
            [
                'nombre' => 'Mala Respuesta en Tickets',
                'departamento_id' => 2,
            ],
            [
                'nombre' => 'Ingreso Al Area Tics Sin Autorización',
                'departamento_id' => 2,
            ],
            [
                'nombre' => 'Entregas De Soluciones Sin Firmas De Recibido',
                'departamento_id' => 2,
            ],
            [
                'nombre' => 'No Documentar Errores De Estructuras',
                'departamento_id' => 2,
            ],
            [
                'nombre' => 'No Comunicar Errores Contables',
                'departamento_id' => 2,
            ],
            [
                'nombre' => 'Realizar Ajustes Contables Sin Autorización',
                'departamento_id' => 2,
            ],
            [
                'nombre' => 'No Participar Eventos SCRUM',
                'departamento_id' => 3,
            ],
            [
                'nombre' => 'Error de Validación',
                'departamento_id' => 3,
            ],
            [
                'nombre' => 'Error de Estandares de Programación',
                'departamento_id' => 3,
            ],
            [
                'nombre' => 'Error de Actualización Todos Sitios',
                'departamento_id' => 3,
            ],
            [
                'nombre' => 'Cambio en la Base sin Autorización',
                'departamento_id' => 3,
            ],
            [
                'nombre' => 'Desarrollos sin OTP',
                'departamento_id' => 3,
            ],
            [
                'nombre' => 'Desarrollos Sin Pruebas - Instructivo',
                'departamento_id' => 3,
            ],
            [
                'nombre' => 'No Existe Bosquejo o Prototipo Historias de Usuario',
                'departamento_id' => 3,
            ],
            [
                'nombre' => 'No cumplir Procesos Empresarials IMP/DES',
                'departamento_id' => 3,
            ],
            [
                'nombre' => 'No Presenta Código Al Coordinador',
                'departamento_id' => 3,
            ],
            [
                'nombre' => 'No Existen Desarrollos Sin Ticket',
                'departamento_id' => 3,
            ],
            [
                'nombre' => 'Entrega de Desarrollo Con Retrasos',
                'departamento_id' => 3,
            ],
            [
                'nombre' => 'Entrega Con Desarrollos Con Errores',
                'departamento_id' => 3,
            ],
            [
                'nombre' => 'Errores de Documentos PAG,DPF,Apertura,Contratos',
                'departamento_id' => 3,
            ],
            [
                'nombre' => 'Entrega de Código Sin Firmas de Recibido',
                'departamento_id' => 3,
            ],
            [
                'nombre' => 'No Documentar Errores',
                'departamento_id' => 3,
            ],
            [
                'nombre' => 'Modificaciones Sin Autorización',
                'departamento_id' => 3,
            ],
            [
                'nombre' => 'Pruebas o Arreglos Fuera de Tics',
                'departamento_id' => 3,
            ],
            [
                'nombre' => 'Reuniones de Trabajo Fuera de Tics',
                'departamento_id' => 3,
            ],
            [
                'nombre' => 'Prohibido Desarrollar Sitios de Producción/Pruebas',
                'departamento_id' => 3,
            ],
			[
                'nombre' => 'No Participar Eventos Scrum',
                'departamento_id' => 4,
            ],
			[
                'nombre' => 'No Registrar Actividades Diarias ERP',
                'departamento_id' => 4,
            ],
			[
                'nombre' => 'Retrasos En Implementaciones BI',
                'departamento_id' => 4,
            ],
			[
                'nombre' => 'No Cumplir con las Actas Entrega Recepción Productos',
                'departamento_id' => 4,
            ],
			[
                'nombre' => 'No Presentar Avances Desarrollo ERP',
                'departamento_id' => 4,
            ],
			[
                'nombre' => 'No Crear Instructivos SGSI',
                'departamento_id' => 4,
            ],
			[
                'nombre' => 'No Presenta Avances MAT-RIESGOS-SI',
                'departamento_id' => 4,
            ],
			[
                'nombre' => 'No Realizar Comites de Innovación',
                'departamento_id' => 4,
            ],
			[
                'nombre' => 'No Realizar Comites SGSI',
                'departamento_id' => 4,
            ],
			[
                'nombre' => 'No Realizar Informes OSI',
                'departamento_id' => 4,
            ],
			[
                'nombre' => 'No Presentar Avances Seguridad Ocupacional',
                'departamento_id' => 4,
            ],
			[
                'nombre' => 'No Presentar Avances De Plan de Seguridad Ocupacional',
                'departamento_id' => 4,
            ],
			[
                'nombre' => 'No Supervisar el SGSI',
                'departamento_id' => 4,
            ],
			[
                'nombre' => 'No Presentar Propuestas de Valor e Innovación',
                'departamento_id' => 4,
            ],
			[
                'nombre' => 'No Presentar Avances y Nuevos Indicadores BI',
                'departamento_id' => 4,
            ],
			[
                'nombre' => 'No Cargar Información SEPS-COOP BI',
                'departamento_id' => 4,
            ],
			[
                'nombre' => 'No Participar Eventos SCRUM',
                'departamento_id' => 6,
            ],
			[
                'nombre' => 'No Registrar Actividades Diarias ERP',
                'departamento_id' => 6,
            ],
			[
                'nombre' => 'No Documentar Aceptación de Proformas',
                'departamento_id' => 6,
            ],
			[
                'nombre' => 'No Entregar Valores Por Cobrar Documentados',
                'departamento_id' => 6,
            ],
			[
                'nombre' => 'No Controlar Procesos de Implementación',
                'departamento_id' => 6,
            ],[
                'nombre' => 'No Controlar Procesos de Capacitación',
                'departamento_id' => 6,
            ],[
                'nombre' => 'No Generar Plan de Ventas',
                'departamento_id' => 6,
            ],[
                'nombre' => 'No Mantener Lista de Potenciales Clientes',
                'departamento_id' => 6,
            ],
			[
                'nombre' => 'No Mantener Indicador de Satisfacción Al Cliente',
                'departamento_id' => 6,
            ],
			[
                'nombre' => 'No Presentar Avances de Procesos',
                'departamento_id' => 6,
            ],
			[
                'nombre' => 'No Presentar Avances de Procesos de SGRO',
                'departamento_id' => 6,
            ],
			[
                'nombre' => 'Pedir Desarrollos Sin Autorización',
                'departamento_id' => 6,
            ],
			[
                'nombre' => 'Ingreso al Area Tics Sin Autorización',
                'departamento_id' => 6,
            ],
			[
                'nombre' => 'No Presenta Investigación Innovación Marketing',
                'departamento_id' => 6,
            ],
			[
                'nombre' => 'No Revisar Redes Sociales',
                'departamento_id' => 6,
            ],
			[
                'nombre' => 'No Revisar Fechas Importantes Festivas',
                'departamento_id' => 6,
            ],
			[
                'nombre' => 'Incuplimiento de Fechas de Entrega',
                'departamento_id' => 6,
            ],
			[
                'nombre' => 'No Realizar Informes de Plan de Marketing',
                'departamento_id' => 6,
            ],
			[
                'nombre' => 'No Diseño de Matriz de Aniversarios',
                'departamento_id' => 6,
            ],
			[
                'nombre' => 'No Revisar Publicidad Sincronizada Redes',
                'departamento_id' => 6,
            ],
			[
                'nombre' => 'No Revisar Publicidad Sincronizada Redes',
                'departamento_id' => 6,
            ],
			[
                'nombre' => 'No Participar Eventos SCRUM',
                'departamento_id' => 8,
            ],
			[
                'nombre' => 'No Registrar Actividades Diarias ERP',
                'departamento_id' => 8,
            ],
			[
                'nombre' => 'No Presenta Informes de Soporte Técnico',
                'departamento_id' => 8,
            ],
			[
                'nombre' => 'Retrazos En Facturación Mensaul y Quincenal',
                'departamento_id' => 8,
            ],
			[
                'nombre' => 'No Subir Providencias Judiciales',
                'departamento_id' => 8,
            ],
			[
                'nombre' => 'No Realizar Seguimiento Cobranzas',
                'departamento_id' => 8,
            ],
			[
                'nombre' => 'No Realizar Seguimiento Objetivos Estrategicos',
                'departamento_id' => 8,
            ],
			[
                'nombre' => 'No Realizar Analisis Presupuestario/Liquidez',
                'departamento_id' => 8,
            ],
			[
                'nombre' => 'No Realizar Reuniones Generales',
                'departamento_id' => 8,
            ],
			[
                'nombre' => 'No Realizar Comité de Tecnologis',
                'departamento_id' => 8,
            ],
			[
                'nombre' => 'No Realizar Comites de Seguridades de la Información',
                'departamento_id' => 8,
            ],
			[
                'nombre' => 'No Realizar Comites de Innovación',
                'departamento_id' => 8,
            ],
			[
                'nombre' => 'No Realizar Comite de Procesos y Comercial',
                'departamento_id' => 8,
            ],
			[
                'nombre' => 'No Entrega de Manual de Funciones',
                'departamento_id' => 8,
            ],
			[
                'nombre' => 'No Desarrollar PETI',
                'departamento_id' => 8,
            ],
			[
                'nombre' => 'Ingreso al Area Tics sin Autorización',
                'departamento_id' => 8,
            ],
			[
                'nombre' => 'No Actualizar PAO 2025',
                'departamento_id' => 8,
            ],
			[
                'nombre' => 'No Desarrollo Planes de Contingencia',
                'departamento_id' => 8,
            ],
			[
                'nombre' => 'No Certifiacion ISO',
                'departamento_id' => 8,
            ],
			

			
			
			
			
			


        ];

        foreach ($parametros as $parametro) {
            $parametroModel = new Parametro();
            $parametroModel->fill($parametro);
            $parametroModel->save();
        }
    }
}
