<table class="table table-bordered table-striped table-hover">
    <tbody>
        <tr>
            <th>ID</th>
            <td>{{ $actividades->id }}</td>
        </tr>
        <tr>
            <th>Cliente</th>
            <td>{{ $actividades->cliente ? $actividades->cliente->nombre : 'N/A' }}</td>
        </tr>
        <tr>
            <th>Empleado</th>
            <td>{{ $actividades->empleado ? $actividades->empleado->nombre1 . ' ' . $actividades->empleado->apellido1 : 'N/A' }}</td>
        </tr>
        <tr>
            <th>Descripción</th>
            <td>{{ $actividades->descripcion }}</td>
        </tr>
        <tr>
            <th>Código Osticket</th>
            <td>{{ $actividades->codigo_osticket }}</td>
        </tr>
        <tr>
            <th>Frecuencia</th>
            <td>{{ $actividades->semanal_diaria }}</td>
        </tr>
        <tr>
            <th>Fecha de Inicio</th>
            <td>{{ $actividades->fecha_inicio ? $actividades->fecha_inicio->format('d/m/Y') : 'N/A' }}</td>
        </tr>
        <tr>
            <th>Avance</th>
            <td>{{ $actividades->avance }}%</td>
        </tr>
        <tr>
            <th>Observaciones</th>
            <td>{{ $actividades->observaciones }}</td>
        </tr>
        <tr>
            <th>Estado</th>
            <td>{{ $actividades->estado }}</td>
        </tr>
        <tr>
            <th>Tiempo Estimado</th>
            <td>{{ $actividades->tiempo_estimado }} minutos</td>
        </tr>
        <tr>
            <th>Fecha de Fin</th>
            <td>{{ $actividades->fecha_fin ? $actividades->fecha_fin->format('d/m/Y') : 'N/A' }}</td>
        </tr>
        <tr>
            <th>Repetitivo</th>
            <td>{{ $actividades->repetitivo ? 'Sí' : 'No' }}</td>
        </tr>
        <tr>
            <th>Prioridad</th>
            <td>{{ $actividades->prioridad }}</td>
        </tr>
        <tr>
            <th>Departamento</th>
            <td>{{ $actividades->departamento->nombre }}</td>
        </tr>
        <tr>
            <th>Tipo de Error</th>
            <td>{{ $actividades->error }}</td>
        </tr>
        <tr>
            <th>Creación de la Actividad</th>
            <td>{{ $actividades->created_at ? $actividades->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
        </tr>
        <tr>
            <th>Actualización de la Actividad</th>
            <td>{{ $actividades->updated_at ? $actividades->updated_at->format('d/m/Y H:i') : 'N/A' }}</td>
        </tr>
    </tbody>
</table>
