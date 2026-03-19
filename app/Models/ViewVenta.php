<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ViewVenta
 * 
 * @property int|null $id
 * @property int|null $mueblerias_id
 * @property int $usuarios_id
 * @property int $clientes_id
 * @property string|null $muebleria
 * @property string|null $mueble
 * @property string|null $cliente
 * @property string|null $vendedor
 * @property float|null $precio
 * @property int|null $numero_abonos
 * @property float $abonos
 * @property float|null $resta
 * @property string|null $forma_pago
 * @property string|null $tipo_pago
 * @property float|null $cantidad
 * @property string|null $color
 * @property Carbon|null $fecha_venta
 * @property Carbon|null $fecha_entrega
 * @property int|null $comision
 *
 * @package App\Models
 */
class ViewVenta extends Model
{
	protected $table = 'view_ventas';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int',
		'mueblerias_id' => 'int',
		'usuarios_id' => 'int',
		'clientes_id' => 'int',
		'precio' => 'float',
		'numero_abonos' => 'int',
		'abonos' => 'float',
		'resta' => 'float',
		'cantidad' => 'float',
		'fecha_venta' => 'datetime',
		'fecha_entrega' => 'datetime',
		'comision' => 'int'
	];

	protected $fillable = [
		'id',
		'mueblerias_id',
		'usuarios_id',
		'clientes_id',
		'muebleria',
		'mueble',
		'cliente',
		'vendedor',
		'precio',
		'numero_abonos',
		'abonos',
		'resta',
		'forma_pago',
		'tipo_pago',
		'cantidad',
		'color',
		'fecha_venta',
		'fecha_entrega',
		'comision'
	];
}
