<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Abono
 * 
 * @property int $abonos_id
 * @property int|null $ventas_id
 * @property int|null $usuarios_id
 * @property Carbon|null $fecha
 * @property int|null $codigo_ventas
 * @property int|null $pago
 *
 * @package App\Models
 */
class Abono extends Model
{
	protected $table = 'abonos';
	protected $primaryKey = 'abonos_id';
	public $timestamps = false;

	protected $casts = [
		'ventas_id' => 'int',
		'usuarios_id' => 'int',
		'fecha' => 'datetime',
		'codigo_ventas' => 'int',
		'pago' => 'int'
	];

	protected $fillable = [
		'ventas_id',
		'usuarios_id',
		'fecha',
		'codigo_ventas',
		'pago'
	];
}
