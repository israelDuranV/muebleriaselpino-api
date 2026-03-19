<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Venta
 * 
 * @property int $ventas_id
 * @property int|null $mueblerias_id
 * @property int|null $muebles_id
 * @property int|null $usuarios_id
 * @property int|null $clientes_id
 * @property string|null $forma_pago
 * @property string|null $tipo_pago
 * @property int|null $cantidad
 * @property string|null $color
 * @property int|null $precio
 * @property int|null $descuento
 * @property int|null $tipo_pago_id
 * @property Carbon|null $fecha_venta
 * @property int|null $comision
 * @property Carbon|null $fecha_entrega
 * @property int|null $primer_abono
 * @property int|null $codigo_ventas
 * @property int|null $pagado
 * 
 * @property Muebleria|null $muebleria
 * @property Mueble|null $mueble
 * @property Usuario|null $usuario
 * @property Cliente|null $cliente
 *
 * @package App\Models
 */
class Venta extends Model
{
	protected $table = 'ventas';
    protected $primaryKey = 'ventas_id';
    public $timestamps = false;

    protected $fillable = [
        'mueblerias_id',
        'muebles_id',
        'usuarios_id',
        'clientes_id',
        'forma_pago',
        'tipo_pago',
        'cantidad',
        'color',
        'precio',
        'primer_abono',
        'pagado',
        'fecha_venta',
        'comision',
        'fecha_entrega',
        'codigo_ventas'
    ];

    protected $casts = [
        'fecha_venta' => 'datetime',
        'fecha_entrega' => 'date',
        'precio' => 'integer',
        'cantidad' => 'integer',
        'comision' => 'integer',
        'primer_abono' => 'integer',
        'pagado' => 'boolean'
    ];

    // Relaciones
    public function muebleria()
    {
        return $this->belongsTo(Muebleria::class, 'mueblerias_id');
    }

    public function mueble()
    {
        return $this->belongsTo(Mueble::class, 'muebles_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuarios_id');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'clientes_id');
    }

    public function abonos()
    {
        return $this->hasMany(Abono::class, 'ventas_id');
    }

    /**
     * Obtener el siguiente código de venta disponible
     */
    public static function obtenerSiguienteCodigo()
    {
        $resultado = DB::select('CALL sp_consultaVenta(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            'CODIGO',
            null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null
        ]);

        return $resultado[0]->codigo ?? 1;
    }

    /**
     * Guardar una nueva venta usando el procedimiento almacenado
     */
    public static function guardarVenta(array $datos)
    {
        DB::statement('CALL sp_consultaVenta(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            'SAVE',
            $datos['empleado_id'] ?? null,
            $datos['cliente_id'] ?? null,
            $datos['muebleria_id'] ?? null,
            $datos['precio'] ?? null,
            $datos['terminado'] ?? null,
            $datos['cantidad'] ?? null,
            $datos['color'] ?? null,
            $datos['tipo_pago'] ?? null,
            $datos['forma_pago'] ?? null,
            $datos['fecha_entrega'] ?? null,
            $datos['comision'] ?? null,
            $datos['mueble_id'] ?? null,
            $datos['primer_pago'] ?? null,
            null, // fecha_inicio
            null, // fecha_final
            null, // id
            $datos['codigo_ventas'] ?? null
        ]);

        return true;
    }

    /**
     * Obtener reporte completo de ventas
     */
    public static function obtenerReporte()
    {
        return DB::select('CALL sp_consultaVenta(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            'REPORTE',
            null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null
        ]);
    }

    /**
     * Obtener reporte de ventas filtrado por fechas
     */
    public static function obtenerReportePorFechas($fechaInicio, $fechaFinal)
    {
        return DB::select('CALL sp_consultaVenta(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            'REPORTEFORDATE',
            null, null, null, null, null, null, null, null, null, null, null, null, null,
            $fechaInicio,
            $fechaFinal,
            null, null
        ]);
    }

    /**
     * Obtener inventario disponible por mueble
     */
    public static function obtenerInventarioPorMueble($muebleId)
    {
        return DB::select('CALL sp_consultaVenta(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            'INVENTARIOXMUEBLE',
            null, null, null, null, null, null, null, null, null, null, null,
            $muebleId,
            null, null, null, null, null
        ]);
    }

    /**
     * Eliminar venta por código
     */
    public static function eliminarVenta($codigoVenta)
    {
        DB::statement('CALL sp_consultaVenta(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            'DELETE',
            null, null, null, null, null, null, null, null, null, null, null, null, null, null, null,
            $codigoVenta,
            null
        ]);

        return true;
    }

    /**
     * Scope para ventas pagadas
     */
    public function scopePagadas($query)
    {
        return $query->where('pagado', 1);
    }

    /**
     * Scope para ventas pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('pagado', 0);
    }

    /**
     * Scope para ventas por fecha
     */
    public function scopePorFecha($query, $fechaInicio, $fechaFinal)
    {
        return $query->whereBetween('fecha_venta', [$fechaInicio, $fechaFinal]);
    }

    /**
     * Calcular el total abonado de esta venta
     */
    public function totalAbonado()
    {
        return $this->abonos()->sum('pago');
    }

    /**
     * Calcular el saldo pendiente
     */
    public function saldoPendiente()
    {
        return $this->precio - $this->totalAbonado();
    }

    /**
     * Verificar si la venta está completamente pagada
     */
    public function estaCompletamentePagada()
    {
        return $this->saldoPendiente() <= 0;
    }

	public function tipo_pago()
	{
		return $this->belongsTo(TipoPago::class);
	}
}
