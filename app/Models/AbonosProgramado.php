<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AbonosProgramado
 * 
 * @property int $abonos_id
 * @property int|null $usuarios_id
 * @property Carbon|null $fecha
 * @property int|null $codigo_ventas
 * @property int|null $pago
 * @property int|null $pagado
 *
 * @package App\Models
 */
class AbonosProgramado extends Model
{
	protected $table = 'abonos_programados';
	protected $primaryKey = 'abonos_id';
	public $timestamps = false;

	protected $casts = [
		'usuarios_id' => 'int',
		'fecha' => 'datetime',
		'codigo_ventas' => 'int',
		'pago' => 'int',
		'pagado' => 'int'
	];

	protected $fillable = [
		'usuarios_id',
		'fecha',
		'codigo_ventas',
		'pago',
		'pagado'
	];
}
