// +--------------------------------------------------------+
// Nombre del Proyecto: ERP
// Modulo: Actividades
// Version: 1.0
// Desarrollado por: Karol Macas
// Fecha de Inicio:
// Ultima Modificación: 
// +--------------------------------------------------------+

$(document).ready(function() {
    $('#actividades-table').DataTable({
        responsive: true,
        pageLength: 10, // Número de filas por página
        lengthMenu: [5, 10, 25, 50], // Opciones de paginación
        language: {
            search: "Buscar:",
            lengthMenu: "Mostrar _MENU_ actividades",
            info: "Mostrando _START_ a _END_ de _TOTAL_ actividades",
            paginate: {
                first: "Primera",
                last: "Última",
                next: "Siguiente",
                previous: "Anterior"
            }
        },
        order: [
            [0, 'desc']
        ]
    });
});
