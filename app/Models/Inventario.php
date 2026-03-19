<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Inventario
 * 
 * @property int $inventarios_id
 * @property int|null $muebles_id
 * @property Carbon|null $fecha
 * @property string|null $tipo
 * @property int|null $cantidad
 * @property int|null $usuarios_id
 * @property int|null $mueblerias_id
 * @property string|null $comentario
 * @property string|null $comienzo
 * @property string|null $termino
 * 
 * @property Mueble|null $mueble
 * @property Usuario|null $usuario
 * @property Muebleria|null $muebleria
 *
 * @package App\Models
 */
class Inventario extends Model
{
	protected $table = 'inventarios';
	protected $primaryKey = 'inventarios_id';
	public $timestamps = false;

	protected $casts = [
		'muebles_id' => 'int',
		'fecha' => 'datetime',
		'cantidad' => 'int',
		'usuarios_id' => 'int',
		'mueblerias_id' => 'int'
	];

	protected $fillable = [
		'muebles_id',
		'fecha',
		'tipo',
		'cantidad',
		'usuarios_id',
		'mueblerias_id',
		'comentario',
		'comienzo',
		'termino'
	];

	public function mueble()
	{
		return $this->belongsTo(Mueble::class, 'muebles_id');
	}

	public function usuario()
	{
		return $this->belongsTo(Usuario::class, 'usuarios_id');
	}

	public function muebleria()
	{
		return $this->belongsTo(Muebleria::class, 'mueblerias_id');
	}
}
