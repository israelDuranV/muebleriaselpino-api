<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Pedido extends Model
{
    protected $table = 'pedidos';
    protected $primaryKey = 'pedidos_id';
    public $timestamps = false;

    protected $casts = [
        'muebles_id' => 'int',
        'usuario_id' => 'int',
        'mueblerias_id' => 'int',
        'cantidad' => 'int',
        'cantidad_inicial' => 'int',
        'fecha' => 'datetime',
        'fecha_entrega' => 'datetime',
        'produccion' => 'int',
        'id_venta' => 'int',
        'codigo_pedido' => 'int'
    ];

    protected $fillable = [
        'muebles_id',
        'usuario_id',
        'mueblerias_id',
        'cantidad',
        'cantidad_inicial',
        'fecha',
        'descripcion',
        'fecha_entrega',
        'produccion',
        'id_venta',
        'comprobante',
        'codigo_pedido'
    ];
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id', 'usuario_id');
    }

    public function inventarios()
    {
        return $this->hasMany(InventarioGeneral::class, 'pedidos_id', 'pedidos_id');
    }
    public function mueble()
    {
        return $this->belongsTo(Mueble::class, 'muebles_id', 'muebles_id');
    }

    /**
     * Relación con Usuario
     */

    /**
     * Relación con Muebleria
     */
    public function muebleria()
    {
        return $this->belongsTo(Muebleria::class, 'mueblerias_id', 'mueblerias_id');
    }

    // ==================== MÉTODOS DEL STORED PROCEDURE ====================

    /**
     * GET - Obtener todos los pedidos activos (sin producción)
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getPedidos()
    {
        return DB::select('CALL sp_consultaPedidos(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            'GET',      // _accion
            null,       // _mueble
            null,       // _usuario
            null,       // _muebleria
            null,       // _cantidad
            null,       // _fecha
            null,       // _produccion
            null,       // _pedido
            null,       // _fecha_produccion
            null,       // _estatus
            null,       // _procedencia
            null,       // _fecha_comienzo
            null,       // _fecha_termino
            null,       // _fecha_traspaso
            null,       // _fecha_entrega
            null,       // _comprobante
            null,       // _descripcion
            null,       // _codigo
            null        // _id
        ]);
    }

    /**
     * CODIGO - Obtener el siguiente código de pedido
     *
     * @return int
     */
    public static function getNextCodigoPedido()
    {
        $result = DB::select('CALL sp_consultaPedidos(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            'CODIGO',   // _accion
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null
        ]);

        return $result[0]->codigo ?? 1;
    }

    /**
     * SAVE - Guardar un nuevo pedido
     *
     * @param array $data
     * @return bool
     */
    public static function savePedido(array $data)
    {
        try {
            $codigoPedido = self::getNextCodigoPedido();

            DB::statement('CALL sp_consultaPedidos(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                'SAVE',                             // _accion
                $data['muebles_id'],                // _mueble
                $data['usuario_id'],                // _usuario
                $data['muebleria_id'],             // _muebleria
                $data['cantidad'],                  // _cantidad
                null,                               // _fecha (usa NOW() en SP)
                null,                               // _produccion
                null,                               // _pedido
                null,                               // _fecha_produccion
                null,                               // _estatus
                null,                               // _procedencia
                null,                               // _fecha_comienzo
                null,                               // _fecha_termino
                null,                               // _fecha_traspaso
                $data['fecha'] ?? null,     // _fecha_entrega
                null,                               // _comprobante
                $data['descripcion'] ?? null,       // _descripcion
                $codigoPedido,                      // _codigo
                null                                // _id

            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Error en savePedido: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * EDIT - Actualizar un pedido existente
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public static function editPedido(int $id, array $data)
    {
        try {
            $result = DB::select('CALL sp_consultaPedidos(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                'EDIT',                             // _accion
                $data['muebles_id'],                // _mueble
                $data['usuario_id'],                // _usuario
                $data['muebleria_id'],              // _muebleria
                $data['cantidad'],                  // _cantidad
                null,                               // _fecha
                null,                               // _produccion
                null,                               // _pedido
                null,                               // _fecha_produccion
                null,                               // _estatus
                null,                               // _procedencia
                null,                               // _fecha_comienzo
                null,                               // _fecha_termino
                null,                               // _fecha_traspaso
                $data['fecha'] ?? null,             // _fecha_entrega
                null,                               // _comprobante
                $data['descripcion'] ?? '',       // _descripcion
                null,                               // _codigo
                $id                                 // _id
            ]);

            return end($result);
        } catch (\Exception $e) {
            Log::error('Error en editPedido: ' . $e->getMessage());
            return false;
        }
    }
    public static function deletePedido(int $id)
    {
        try {
            $result = DB::select('CALL sp_consultaPedidos(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                'DELETE',                             // _accion
                null,                // _mueble
                null,                // _usuario
                null,             // _muebleria
                null,                  // _cantidad
                null,                               // _fecha
                null,                               // _produccion
                null,                               // _pedido
                null,                               // _fecha_produccion
                null,                               // _estatus
                null,                               // _procedencia
                null,                               // _fecha_comienzo
                null,                               // _fecha_termino
                null,                               // _fecha_traspaso
                null,     // _fecha_entrega
                null,                               // _comprobante
                null,       // _descripcion
                null,                               // _codigo
                $id                                 // _id
            ]);
            return end($result);
        } catch (\Exception $e) {
            Log::error('Error en deletePedido: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * SEND - Enviar pedido a producción/inventario
     *
     * @param array $data
     * @return bool
     */
    public static function sendToProduccion($id, array $data)
    {
        try {
            $result =  DB::select('CALL sp_consultaPedidos(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                'SEND',                             // _accion
                $data['muebles_id'],                // _mueble
                $data['usuario_id'],                // _usuario
                $data['muebleria_id'],             // _muebleria
                $data['cantidad'],                  // _cantidad
                $data['fecha'],                     // _fecha
                null,                               // _produccion
                null,                               // _pedido
                null,                               // _fecha_produccion (usa NOW())
                1,                                  // _estatus
                null,                               // _procedencia
                null,                               // _fecha_comienzo
                null,                               // _fecha_termino
                null,                     // _fecha_traspaso
                null,                               // _fecha_entrega
                null,                               // _comprobante
                $data['descripcion'] ?? null,       // _descripcion
                $data['codigo'],             // _codigo
                $id                                 // _id
            ]);

            return end($result);
        } catch (\Exception $e) {
            Log::error('Error en sendToProduccion: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * DELETE - Disminuir cantidad de pedido
     *
     * @param int $pedidoId
     * @param int $muebleId
     * @return bool
     */
    public static function decrementPedido(int $pedidoId, int $muebleId)
    {
        try {
            DB::statement('CALL sp_consultaPedidos(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                'DECREMENT',   // _accion
                $muebleId,  // _mueble
                null,       // _usuario
                null,       // _muebleria
                null,       // _cantidad
                null,       // _fecha
                null,       // _produccion
                $pedidoId,  // _pedido
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Error en decrementPedido: ' . $e->getMessage());
            return false;
        }
    }

    // ==================== SCOPES ====================

    /**
     * Scope para pedidos activos (sin producción)
     */
    public function scopeActivos($query)
    {
        return $query->where('produccion', 0)->where('cantidad', '>', 0);
    }

    /**
     * Scope para pedidos en producción
     */
    public function scopeEnProduccion($query)
    {
        return $query->where('produccion', 1);
    }

    /**
     * Scope para filtrar por mueblería
     */
    public function scopeByMuebleria($query, $muebleriaId)
    {
        return $query->where('mueblerias_id', $muebleriaId);
    }

    /**
     * Scope para filtrar por usuario
     */
    public function scopeByUsuario($query, $usuarioId)
    {
        return $query->where('usuario_id', $usuarioId);
    }
}
