<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\FiltroController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\MueblesController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProduccionController;
use App\Http\Controllers\PromocionController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::get('produccion', [ProduccionController::class, 'getAllProduccion']);
Route::middleware('auth:sanctum')->group(function () {

    Route::post('logout', [AuthController::class, 'logout']);

    Route::apiResource('users', UserController::class);

    // Rutas adicionales para manejo de imagen de perfil
    Route::post('users/{id}/profile-image', [UserController::class, 'updateProfileImage'])
        ->name('users.update-profile-image');

    Route::delete('users/{id}/profile-image', [UserController::class, 'deleteProfileImage'])
        ->name('users.delete-profile-image');

    // Rutas para manejo de mueblerías
    Route::post('users/{id}/mueblerías', [UserController::class, 'assignMueblerías'])
        ->name('users.assign-mueblerías');

    Route::post('users/{id}/primary-muebleria', [UserController::class, 'setPrimaryMuebleria'])
        ->name('users.set-primary-muebleria');

    // GET - Listar pedidos activos
    Route::get('/pedidos', [PedidoController::class, 'index'])
        ->name('pedidos.index');

    // CODIGO - Obtener siguiente código de pedido
    Route::get('/pedidos/next-codigo', [PedidoController::class, 'getNextCodigo'])
        ->name('pedidos.next-codigo');

    // SAVE - Crear nuevo pedido
    Route::post('/pedidos', [PedidoController::class, 'store'])
        ->name('pedidos.store');

    // EDIT - Actualizar pedido
    Route::put('/pedidos/{id}', [PedidoController::class, 'update'])
        ->name('pedidos.update');

    // SEND - Enviar pedido a producción
    Route::put('/pedidos/send/{id}', [PedidoController::class, 'sendToProduccion'])
        ->name('pedidos.sendproduccion');

    // DELETE - Decrementar cantidad
    Route::delete('/pedidos/{id}', [PedidoController::class, 'destroy'])
        ->name('pedidos.destroy');

    Route::prefix('produccion')->group(function () {
        Route::get('/',       [ProduccionController::class, 'index']);
        Route::post('/start/{id}', [ProduccionController::class, 'start']);
        Route::post('/end/{id}',   [ProduccionController::class, 'end']);
        Route::post('/send',  [ProduccionController::class, 'send']);
    });
    // ========== RUTAS ADICIONALES (usando Eloquent) ==========

    // Obtener un pedido específico
    Route::get('/pedidos/{id}', [PedidoController::class, 'show'])
        ->name('pedidos.show');

    // Pedidos por mueblería
    Route::get('/pedidos/muebleria/{muebleria_id}', [PedidoController::class, 'byMuebleria'])
        ->name('pedidos.by-muebleria');

    // Pedidos por usuario
    Route::get('/pedidos/usuario/{usuario_id}', [PedidoController::class, 'byUsuario'])
        ->name('pedidos.by-usuario');

    // Estadísticas de pedidos
    Route::get('/pedidos/stats/general', [PedidoController::class, 'estadisticas'])
        ->name('pedidos.estadisticas');

    // GETMUEBLES - Listar todos los muebles
    Route::get('/muebles', [MueblesController::class, 'index'])
        ->name('muebles.index');
    Route::get('/getmuebles', [MueblesController::class, 'getMuebles'])
        ->name('muebles.get');

    // GETMUEBLE - Obtener un mueble específico
    Route::get('/muebles/{id}', [MueblesController::class, 'show'])
        ->name('muebles.show');

    // GETDEPARTAMENTO - Obtener muebles por departamento
    Route::get('/muebles/departamento/{departamento_id}', [MueblesController::class, 'byDepartamento'])
        ->name('muebles.by-departamento');

    // INSERTMUEBLES - Crear nuevo mueble
    Route::post('/muebles', [MueblesController::class, 'store'])
        ->name('muebles.store');

    // UPDATE - Actualizar mueble
    Route::put('/muebles/{id}', [MueblesController::class, 'update'])
        ->name('muebles.update');

    // DELETE - Eliminar mueble
    Route::delete('/muebles/{id}', [MueblesController::class, 'destroy'])
        ->name('muebles.destroy');

    // Agregar foto a mueble
    Route::post('/muebles/{id}/fotos', [MueblesController::class, 'addFoto'])
        ->name('muebles.add-foto');

    // DELETEFOTO - Eliminar foto específica
    Route::delete('/muebles/{id}/fotos', [MueblesController::class, 'deleteFoto'])
        ->name('muebles.delete-foto');

    // ========== RUTAS ADICIONALES (usando Eloquent) ==========

    // Filtrar muebles con parámetros avanzados
    Route::get('/muebles/filtrar/advanced', [MueblesController::class, 'filtrar'])
        ->name('muebles.filtrar');

    // Estadísticas de muebles
    Route::get('/muebles/stats/general', [MueblesController::class, 'estadisticas'])
        ->name('muebles.estadisticas');

    Route::prefix('usuarios')->group(function () {
        Route::get('/', [UsuarioController::class, 'index']); // Listar todos
        Route::post('/', [UsuarioController::class, 'store']); // Crear
        Route::get('/{id}', [UsuarioController::class, 'show']); // Ver uno
        Route::put('/{id}', [UsuarioController::class, 'update']); // Actualizar
        Route::delete('/{id}', [UsuarioController::class, 'destroy']); // Desactivar

        // Perfil y configuración
        Route::patch('/{id}/perfil', [UsuarioController::class, 'actualizarPerfil']);
        Route::post('/cambiar-password', [UsuarioController::class, 'cambiarPassword']);

        // Filtros y búsquedas
        Route::get('/rol/{rolId}', [UsuarioController::class, 'porRol']);
        Route::get('/{usuarioId}/acceso-muebleria/{muebleriaId}', [UsuarioController::class, 'verificarAccesoMuebleria']);
    });
    Route::post('imagenes',        [ImageController::class, 'store']);
    Route::delete('imagenes',      [ImageController::class, 'destroy']);
   
    Route::prefix('filtros')->group(function () {

        // Listar catálogos disponibles
        Route::get('/catalogos', [FiltroController::class, 'listarCatalogos']);

        // Obtener todos los catálogos
        Route::get('/todos', [FiltroController::class, 'obtenerTodos']);

        // Obtener múltiples catálogos específicos
        Route::post('/varios', [FiltroController::class, 'obtenerVarios']);

        // Obtener un catálogo específico (formato estándar)
        Route::get('/{catalogo}', [FiltroController::class, 'obtener']);

        // Obtener catálogo optimizado con cache
        Route::get('/{catalogo}/cache', [FiltroController::class, 'obtenerConCache']);

        // Formatos específicos para diferentes frontends
        Route::get('/{catalogo}/select', [FiltroController::class, 'obtenerParaSelect']);
        Route::get('/{catalogo}/select2', [FiltroController::class, 'obtenerParaSelect2']);
        Route::get('/{catalogo}/react-select', [FiltroController::class, 'obtenerParaReactSelect']);

        // Búsqueda
        Route::get('/{catalogo}/buscar', [FiltroController::class, 'buscar']);
        Route::get('/{catalogo}/item/{id}', [FiltroController::class, 'buscarPorId']);

        // Gestión de cache
        Route::delete('/cache', [FiltroController::class, 'limpiarCache']);
    });
    Route::get('catalogos', [CatalogoController::class, 'catalogos']);

    // ── Obtener todos los catálogos con sus datos ──────────────────────────────
    Route::get('catalogos/all', [CatalogoController::class, 'all']);
    
    // ── CRUD + búsqueda + stats por catálogo ──────────────────────────────────
    Route::prefix('catalogos/{catalogo}')->group(function () {
        Route::get('/',         [CatalogoController::class, 'index']);    // GET    /api/catalogos/materiales
        Route::post('/',        [CatalogoController::class, 'store']);    // POST   /api/catalogos/materiales
        Route::get('/search',   [CatalogoController::class, 'search']);   // GET    /api/catalogos/materiales/search?q=pino
        Route::get('/stats',    [CatalogoController::class, 'stats']);    // GET    /api/catalogos/materiales/stats
        Route::get('/{id}',     [CatalogoController::class, 'show']);     // GET    /api/catalogos/materiales/1
        Route::put('/{id}',     [CatalogoController::class, 'update']);   // PUT    /api/catalogos/materiales/1
        Route::delete('/{id}',  [CatalogoController::class, 'destroy']); // DELETE /api/catalogos/materiales/1
    });
    Route::prefix('promociones')->group(function () {

        // CRUD básico
        Route::get('/', [PromocionController::class, 'index']); // Todas
        Route::post('/', [PromocionController::class, 'store']); // Crear
        Route::put('/{id}', [PromocionController::class, 'update']); // Actualizar
        Route::delete('/{id}', [PromocionController::class, 'destroy']); // Eliminar

        // Promoción activa
        Route::get('/activa', [PromocionController::class, 'activa']);

        // Cálculo de descuentos
        Route::post('/calcular-descuento', [PromocionController::class, 'calcularDescuento']);
        Route::post('/todos-descuentos', [PromocionController::class, 'todosLosDescuentos']);
        Route::post('/mejor-descuento', [PromocionController::class, 'mejorDescuento']);

        // Filtros por estado
        Route::get('/estado/{estado}', [PromocionController::class, 'porEstado']); // activas, futuras, vencidas

        // Temporadas
        Route::get('/temporadas', [PromocionController::class, 'temporadas']);
        Route::post('/crear-temporada', [PromocionController::class, 'crearPorTemporada']);

        // Validaciones
        Route::post('/validar-fechas', [PromocionController::class, 'validarFechas']);
    });
});
