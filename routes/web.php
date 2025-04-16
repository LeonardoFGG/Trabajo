<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmpleadosController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActividadesController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\RRHHController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\CargosController;
use App\Http\Controllers\RubroController;
use App\Http\Controllers\RolPagoController;
use App\Http\Controllers\DailyController;
use App\Http\Controllers\MatrizCumplimientoController;
use App\Http\Controllers\ParametroController;
use App\Http\Controllers\ImageController;
use App\Models\Image;
use App\Http\Controllers\VacacionController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\PaqueteController;
use App\Http\Controllers\ServicioTecnicoController;

use App\Http\Controllers\PermisoController;
use App\Models\User;
use App\Models\Actividades;

use App\Models\Producto;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

Route::get('/notificaciones', function () {
    $user = auth()->user();

    // Obtener solo las notificaciones no leídas relacionadas con actividades pausadas
    $notificaciones = $user->unreadNotifications->filter(function ($notification) {
        return isset($notification->data['actividad_id']);
    });

    return view('notificaciones.index', compact('notificaciones'));
})->name('notificaciones.index');

Route::get('/notificaciones/read/{id}', function ($id) {
    $user = Auth::user();
    if ($user) {
        $notificacion = $user->notifications->find($id);
        if ($notificacion) {
            $notificacion->markAsRead();
        }
    }
    return redirect()->back();
})->name('notificaciones.read');




Route::get('/actividades/pausadas', function () {
    $user = auth()->user();

    // Obtener las actividades pausadas del empleado
    $actividadesPausadas = Actividades::where('empleado_id', $user->empleado->id)
        ->where('estado', 'PENDIENTE')
        ->get();

    return view('actividades.pausadas', compact('actividadesPausadas'));
})->name('actividades.pausadas');

Route::get('/actividades/pausadas', function () {
    $user = auth()->user();

    // Obtener las actividades pausadas del empleado
    $actividadesPausadas = Actividades::where('empleado_id', $user->empleado->id)
        ->where('estado', 'PENDIENTE')
        ->get();

    // Marcar las notificaciones como leídas
    foreach ($user->unreadNotifications as $notification) {
        $notification->markAsRead();
    }

    return view('actividades.pausadas', compact('actividadesPausadas'));
})->name('actividades.pausadas');


// Ruta de Bienvenida para todos 
Route::get('/', function () {
    $imagen = Image::latest()->first();
    return view('welcome', compact('imagen'));
})->name('welcome');



// Rutas de autenticación
Auth::routes(['register' => false]);  // Desactiva el registro si no lo necesitas

// Redirigir a la vista de bienvenida si alguien intenta acceder al login
Route::get('/login', function () {
    return redirect()->route('welcome');
})->name('login');

// Ruta de cerrar sesion
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Redirigir a la vista de bienvenida si alguien intenta acceder al registro
Route::get('/register', function () {
    return redirect()->route('welcome');
})->name('register');

// Ruta de cierre de sesión
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


Route::patch('/empleados/{id}/toggle-estado', [EmpleadosController::class, 'toggleEstado'])->name('empleados.toggleEstado');


// Rutas que requieren autenticación
Route::middleware(['auth'])->group(function () {
    // Rutas comunes para administradores
    Route::middleware(['role:admin'])->group(function () {

        Route::delete('/vacaciones/{id}', [VacacionController::class, 'eliminarSolicitud'])->name('vacaciones.eliminar');


        Route::get('/permisos/horas', [PermisoController::class, 'indexHoras'])->name('permisos.indexHoras');


        Route::post('/permisos/calcular', [PermisoController::class, 'calcularHoras'])->name('permisos.calcularHoras');

        Route::get('/vacaciones/saldo', [VacacionController::class, 'indexSaldo'])->name('vacaciones.indexSaldo');

        Route::get('/vacaciones/saldo', [VacacionController::class, 'indexSaldo'])->name('vacaciones.indexSaldo');
        Route::post('/vacaciones/crearSaldo', [VacacionController::class, 'crearSaldo'])->name('vacaciones.crearSaldo');
        Route::put('/vacaciones/editarSaldo/{id}', [VacacionController::class, 'editarSaldo'])->name('vacaciones.editarSaldo');
        Route::delete('/vacaciones/eliminarSaldo/{id}', [VacacionController::class, 'eliminarSaldo'])->name('vacaciones.eliminarSaldo');



        // Ruta para mostrar la vista de ventas
        Route::get('/ventas', [VentaController::class, 'index'])->name('Ventas.index');
        Route::patch('/ventas/{id}/reanudar', [VentaController::class, 'reanudar'])->name('ventas.reanudar');

        Route::get('/ventas/lista', [VentaController::class, 'indexLista'])->name('ventas.indexLista');
        // Ruta para guardar la venta (interna o externa)
        Route::post('/ventas/guardar', [VentaController::class, 'guardar'])->name('ventas.guardar');

        Route::post('/ventas/store', [VentaController::class, 'store'])->name('ventas.store');

        Route::get('/rrhh', [RRHHController::class, 'index'])->name('rrhh.index');


        Route::post('/ventas/pausar', [VentaController::class, 'pausar'])->name('ventas.pausar');

        Route::post('/ventas/store', [VentaController::class, 'store'])->name('ventas.store');

        Route::post('/ventas/{venta}/avanzar-contacto', [VentaController::class, 'avanzarAContacto'])
            ->name('ventas.avanzar-contacto');

        // routes/web.php
       

        Route::get('/clientes/{cliente}/productos-completos', [VentaController::class, 'getProductosCliente'])
        ->name('clientes.productos-completos');

        Route::post('/ventas', [VentaController::class, 'store'])->name('ventas.store');
        Route::put('/ventas/{venta}', [VentaController::class, 'update'])->name('ventas.update');
        Route::patch('/ventas/{venta}/pausar', [VentaController::class, 'pausar'])->name('ventas.pausar');

        Route::resource('empleados', EmpleadosController::class)->names([
            'index' => 'empleados.indexEmpleados',
            'store' => 'empleados.store',
            'show' => 'empleados.show',
            'edit' => 'empleados.edit',
            'update' => 'empleados.update',
            'destroy' => 'empleados.destroy',
        ]);

        Route::resource('actividades', ActividadesController::class)->names([
            'index' => 'actividades.indexActividades',
            'store' => 'actividades.store',
            'show' => 'actividades.show',
            'edit' => 'actividades.edit',
            'update' => 'actividades.update',
            'destroy' => 'actividades.destroy',
        ]);

        Route::put('/actividades/{id}/observaciones', [ActividadesController::class, 'updateObservaciones'])->name('actividades.updateObservaciones');

        Route::put('/actividades/{id}/update-tiempo-real', [ActividadesController::class, 'updateTiempoReal'])->name('actividades.updateTiempoReal');


        Route::resource('productos', ProductoController::class)->names([
            'index' => 'productos.index',
            'store' => 'productos.store',
            'show' => 'productos.show',
            'edit' => 'productos.edit',
            'update' => 'productos.update',
            'destroy' => 'productos.destroy',
        ]);

        Route::get('/productos/{id}/calcular-valor', [ProductoController::class, 'calcularValor']);
        Route::post('/clientes/subproductos', [ClienteController::class, 'obtenerSubproductos'])->name('clientes.subproductos');
        Route::get('/productos/{id}/subproductos', [ProductoController::class, 'getSubproductos']);
        Route::resource('clientes', ClienteController::class)->names([
            'index' => 'clientes.index',
            'store' => 'clientes.store',
            'show' => 'clientes.show',
            'edit' => 'clientes.edit',
            'update' => 'clientes.update',
            'destroy' => 'clientes.destroy',
        ]);

        Route::resource('departamentos', DepartamentoController::class)->names([
            'index' => 'departamentos.index',
            'store' => 'departamentos.store',
            'show' => 'departamentos.show',
            'edit' => 'departamentos.edit',
            'update' => 'departamentos.update',
            'destroy' => 'departamentos.destroy',

        ]);

        Route::resource('supervisores', SupervisorController::class)->names([
            'index' => 'supervisores.index',
            'store' => 'supervisores.store',
            'show' => 'supervisores.show',
            'edit' => 'supervisores.edit',
            'update' => 'supervisores.update',
            'destroy' => 'supervisores.destroy',
        ]);
        Route::post('/supervisores/asignar-superior', [SupervisorController::class, 'asignarSupervisorSuperior'])->name('supervisores.asignar-superior');
        Route::get('/supervisores/{id}/subordinados', [SupervisorController::class, 'obtenerSubordinados'])->name('supervisores.obtener-subordinados');

        Route::get('/departamentos/{id}/supervisor', [DepartamentoController::class, 'getSupervisores']);
        Route::get('/supervisores/{departamento_id}', [EmpleadosController::class, 'getSupervisoresPorDepartamento']);
        Route::get('/asignar-supervisor', [DepartamentoController::class, 'asignarSupervisor']);

        Route::get('/supervisores/departamento/{departamento_id}', [SupervisorController::class, 'getSupervisoresPorDepartamento']);


        Route::resource('cargos', CargosController::class)->names([
            'index' => 'cargos.index',
            'store' => 'cargos.store',
            'show' => 'cargos.show',
            'edit' => 'cargos.edit',
            'update' => 'cargos.update',
            'destroy' => 'cargos.destroy',
        ]);

        Route::resource('rubros', RubroController::class)->names([
            'index' => 'rubros.index',
            'store' => 'rubros.store',
            'show' => 'rubros.show',
            'edit' => 'rubros.edit',
            'update' => 'rubros.update',
            'destroy' => 'rubros.destroy',
        ]);

        Route::resource('roles_pago', RolPagoController::class)->names([
            'index' => 'roles_pago.index',
            'store' => 'roles_pago.store',
            'show' => 'roles_pago.show',
            'edit' => 'roles_pago.edit',
            'update' => 'roles_pago.update',
            'destroy' => 'roles_pago.destroy',
        ]);

        Route::resource('daily', DailyController::class)->names([
            'index' => 'daily.index',
            'create' => 'daily.create',
            'store' => 'daily.store',
            'show' => 'daily.show',
            'edit' => 'daily.edit',
            'update' => 'daily.update',
            'destroy' => 'daily.destroy',
        ]);

        Route::resource('matriz_cumplimientos', MatrizCumplimientoController::class)->names([
            'index' => 'matriz_cumplimientos.index',
            'create' => 'matriz_cumplimientos.create',
            'store' => 'matriz_cumplimientos.store',
            'show' => 'matriz_cumplimientos.show',
            'edit' => 'matriz_cumplimientos.edit',
            'update' => 'matriz_cumplimientos.update',
            'destroy' => 'matriz:cumplimientos.destroy',
        ]);

        Route::put('matriz_cumplimientos/{matriz_cumplimiento}', [MatrizCumplimientoController::class, 'update'])->name('matriz_cumplimientos.update');
        Route::put('/matriz_cumplimientos/update_puntos/{id}', [MatrizCumplimientoController::class, 'updatePuntos'])->name('matriz_cumplimientos.updatePuntos');




        Route::resource('parametros', ParametroController::class)->names([
            'index' => 'parametros.index',
            'store' => 'parametros.store',
            'show' => 'parametros.show',
            'edit' => 'parametros.edit',
            'update' => 'parametros.update',
            'destroy' => 'parametros.destroy',
        ]);

        Route::resource('imagen', ImageController::class)->names([
            'index' => 'imagen.index',
        ]);

        Route::post('/imagen', [ImageController::class, 'upload'])->name('images.upload');


        // Ruta para listar permisos (vista index)
        Route::get('/permisos', [PermisoController::class, 'index'])->name('Permisos.index');

        // Ruta para almacenar una nueva solicitud de permiso (crear)
        Route::post('/permisos', [PermisoController::class, 'store'])->name('permisos.store');

        // Ruta para mostrar el formulario de creación de permisos
        Route::get('/permisos/create', [PermisoController::class, 'create'])->name('permisos.create');

        // Ruta para actualizar un permiso
        Route::patch('/permisos/{permiso}', [PermisoController::class, 'update'])->name('permisos.update');

        // Ruta para actualizar solo el estado del permiso
        Route::patch('/permisos/{id}/estado', [PermisoController::class, 'updateEstado'])->name('permisos.updateEstado');

        Route::get('notificaciones/{id}/leer', [ActividadesController::class, 'markAsRead'])->name('notificaciones.read');


        // Ruta para eliminar un permiso


        Route::delete('/permisos/{permiso}', [PermisoController::class, 'destroy'])->name('permisos.destroy');

        Route::get('/productos-por-cliente/{clienteId}', [ActividadesController::class, 'getProductosByCliente']);

        Route::patch('/permisos/{permiso}/update-hora-regreso', [PermisoController::class, 'updateHoraRegreso'])
            ->name('permisos.updateHoraRegreso');
    });

    Route::put('/actividades/{id}/update-tiempo-real', function (Request $request, $id) {
        // Verificar si es admin, o si el usuario es el empleado con ID 3 o 24
        if (Auth::user()->isAdmin() || in_array(Auth::user()->id, [3, 24])) {
            return app(ActividadesController::class)->updateTiempoReal($request, $id);
        }

        // Si el usuario no tiene permiso
        return redirect()->route('actividades.indexActividades')->withErrors(['error' => 'No tienes permisos para realizar esta acción.']);
    })->name('actividades.updateTiempoReal');




    // Rutas que solo puede acceder un empleado
    Route::middleware(['role:empleado'])->group(function () {

        Route::get('notificaciones/{id}/leer', [ActividadesController::class, 'markAsRead'])->name('notificaciones.read');


        //Ruta para ver el home
        Route::get('/home', [AuthController::class, 'home'])->name('home');

        Route::resource('actividades', ActividadesController::class)->names([
            'index' => 'actividades.indexActividades',
            'store' => 'actividades.store',
            'show' => 'actividades.show',
            'update' => 'actividades.update',


        ]);

        Route::put('/actividades/{id}/observaciones', [ActividadesController::class, 'updateObservaciones'])->name('actividades.updateObservaciones');


        Route::resource('daily', DailyController::class)->names([
            'index' => 'daily.index',
            'create' => 'daily.create',
            'store' => 'daily.store',
            'show' => 'daily.show',
            'edit' => 'daily.edit',
            'update' => 'daily.update',
            'destroy' => 'daily.destroy',
        ]);

        Route::resource('matriz_cumplimientos', MatrizCumplimientoController::class)->names([
            'index' => 'matriz_cumplimientos.index',
            'create' => 'matriz_cumplimientos.create',
            'store' => 'matriz_cumplimientos.store',
            'show' => 'matriz_cumplimientos.show',
            'edit' => 'matriz_cumplimientos.edit',
            'update' => 'matriz_cumplimientos.update',
            'destroy' => 'matriz_cumplimientos.destroy',

        ]);
        Route::put('matriz_cumplimientos/{matriz_cumplimiento}', [MatrizCumplimientoController::class, 'update'])->name('matriz_cumplimientos.update');
        Route::post('/matriz_cumplimientos/update_puntos/{id}', [MatrizCumplimientoController::class, 'updatePuntos'])->name('matriz_cumplimientos.updatePuntos');


        Route::resource("vacaciones", VacacionController::class)->names([
            'index' => 'vacaciones.index',
            'store' => 'vacaciones.store',
            'show' => 'vacaciones.show',
            'edit' => 'vacaciones.edit',
            'update' => 'vacaciones.update',
            'destroy' => 'vacaciones.destroy',
        ]);

        // Ruta para listar permisos (vista index)
        Route::get('/permisos', [PermisoController::class, 'index'])->name('permisos.index');

        Route::get('/permisos', [PermisoController::class, 'indexHoras'])->name('permisos.indexHoras');


        // Ruta para almacenar una nueva solicitud de permiso (crear)
        Route::post('/permisos', [PermisoController::class, 'store'])->name('permisos.store');

        // Ruta para mostrar el formulario de creación de permisos
        Route::get('/permisos/create', [PermisoController::class, 'create'])->name('permisos.create');

        Route::patch('/permisos/{permiso}/anexo', [PermisoController::class, 'updateAnexo'])->name('permisos.updateAnexo');

        Route::patch('/permisos/{permiso}', [PermisoController::class, 'update'])->name('permisos.update');

        Route::patch('/permisos/{id}/estado', [PermisoController::class, 'updateEstado'])->name('permisos.updateEstado');




        Route::put('actividades/{id}/avance', [ActividadesController::class, 'updateAvance'])->name('actividades.updateAvance');
        //    //Ruta de update de estado 
        Route::put('actividades/{id}/estado', [ActividadesController::class, 'updateEstado'])->name('actividades.updateEstado');

        Route::post('/actividades/{id}/start-counter', [ActividadesController::class, 'startCounter'])->name('actividades.startCounter');

        Route::get('/empleado/{id}/details', [EmpleadosController::class, 'getEmployeeDetails'])->name('empleado.details');

        Route::get('/productos-por-cliente/{clienteId}', [ActividadesController::class, 'getProductosByCliente']);

        Route::patch('/permisos/{permiso}/update-hora-regreso', [PermisoController::class, 'updateHoraRegreso'])
            ->name('permisos.updateHoraRegreso');
    });

    // Rutas que solo puede acceder un supervisor
    Route::middleware(['auth', 'supervisor'])->group(function () {
        Route::get('/permisos/horas', [PermisoController::class, 'indexHoras'])->name('permisos.indexHoras');

        Route::get('matriz_cumplimientos', [MatrizCumplimientoController::class, 'index'])->name('matriz_cumplimientos.index');
        Route::put('/matriz_cumplimientos/update_puntos/{id}', [MatrizCumplimientoController::class, 'updatePuntos'])->name('matriz_cumplimientos.updatePuntos');

        Route::resource('permisos', PermisoController::class);
        Route::resource('vacaciones', VacacionController::class);

        Route::patch('/permisos/{id}/estado', [PermisoController::class, 'updateEstado'])->name('permisos.updateEstado');

        Route::get('notificaciones/{id}/leer', [ActividadesController::class, 'markAsRead'])->name('notificaciones.read');

        Route::get('/productos-por-cliente/{clienteId}', [ActividadesController::class, 'getProductosByCliente']);

        Route::patch('/permisos/{permiso}/update-hora-regreso', [PermisoController::class, 'updateHoraRegreso'])
            ->name('permisos.updateHoraRegreso');

        Route::patch('/permisos/{permiso}', [PermisoController::class, 'update'])->name('permisos.update');


        Route::post('/permisos/calcular', [PermisoController::class, 'calcularHoras'])->name('permisos.calcularHoras');
    });



    Route::middleware(['check.user:id,8'])->group(function () {
        Route::resource('imagen', ImageController::class)->names([
            'index' => 'imagen.index',
        ]);

        Route::post('/imagen', [ImageController::class, 'upload'])->name('images.upload');
    });

    Route::get('/ventas', [VentaController::class, 'index'])->middleware('checkCargoVendedor');

    Route::get('/ventas', [VentaController::class, 'index'])->name('ventas.index');

    Route::post('/ventas/pausar', [VentaController::class, 'pausar'])->name('ventas.pausar');

    Route::post('/ventas/store', [VentaController::class, 'store'])->name('ventas.store');
    Route::patch('/permisos/{permiso}', [PermisoController::class, 'update'])->name('permisos.update');

    Route::delete('/permisos/{permiso}', [PermisoController::class, 'destroy'])->name('permisos.destroy');

    Route::post('/permisos/{permiso}/toggle-justificacion', [PermisoController::class, 'toggleJustificacion'])->name('permisos.toggle-justificacion');


    // Rutas para vacaciones
    Route::get('/vacaciones', [VacacionController::class, 'index'])->name('vacaciones.index');
    Route::post('/vacaciones/crear', [VacacionController::class, 'crearSolicitud'])->name('vacaciones.crear');
    Route::delete('/vacaciones/{id}', [VacacionController::class, 'eliminarSolicitud'])->name('vacaciones.eliminar');
    Route::put('/vacaciones/{id}', [VacacionController::class, 'aprobarRechazarSolicitud'])->name('vacaciones.aprobar');
    Route::get('/vacaciones/editar/{id}', [VacacionController::class, 'editarSolicitud'])->name('vacaciones.editar');
    Route::patch('/vacaciones/{id}/estado', [VacacionController::class, 'updateEstado'])->name('vacaciones.updateEstado');

    Route::put('/vacaciones/editar/{id}', [VacacionController::class, 'actualizarSolicitud'])->name('vacaciones.actualizar');
    Route::get('/vacaciones/saldo', [VacacionController::class, 'indexSaldo'])->name('vacaciones.indexSaldo');

    Route::resource('productos', ProductoController::class)->except(['show']);
    Route::get('productos/{producto}', [ProductoController::class, 'show'])->name('productos.show');
    Route::post('/productos', [ProductoController::class, 'store'])->name('productos.store');
    Route::post('productos/{producto}/toggle-status', [ProductoController::class, 'toggleStatus'])->name('productos.toggle-status');
    Route::delete('/productos/{producto}', [ProductoController::class, 'destroy'])->name('productos.destroy');
    Route::put('/productos/{producto}', [ProductoController::class, 'update'])->name('productos.update');

    //paquetes
    Route::resource('paquetes', PaqueteController::class)->names([
        'index' => 'paquetes.index',
        'store' => 'paquetes.store',
        'show' => 'paquetes.show',
        'edit' => 'paquetes.edit',
        'update' => 'paquetes.update',
        'destroy' => 'paquetes.destroy',
    ]);


  

    Route::get('/actividades/servicio-hora/exportar/{formato}', [ActividadesController::class, 'exportarServicioHora'])
    ->name('actividades.exportar.servicio-hora');

});
