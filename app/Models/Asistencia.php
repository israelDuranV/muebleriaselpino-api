<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Asistencia
 * 
 * @property int $asistencias_id
 * @property string|null $tipo
 * @property Carbon|null $fecha
 * @property Carbon|null $dia
 * @property float|null $latitud
 * @property float|null $longitud
 * @property int|null $empleado
 * @property int|null $muebleria
 *
 * @package App\Models
 */
class Asistencia extends Model
{
	protected $table = 'asistencias';
	protected $primaryKey = 'asistencias_id';
	public $timestamps = false;

	protected $casts = [
		'fecha' => 'datetime',
		'dia' => 'datetime',
		'latitud' => 'float',
		'longitud' => 'float',
		'empleado' => 'int',
		'muebleria' => 'int'
	];

	protected $fillable = [
		'tipo',
		'fecha',
		'dia',
		'latitud',
		'longitud',
		'empleado',
		'muebleria'
	];
}
