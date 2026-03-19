<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Cliente
 * 
 * @property int $clientes_id
 * @property string|null $nombres
 * @property string|null $paterno
 * @property string|null $materno
 * @property string|null $tel_local
 * @property string|null $celular
 * @property string|null $comprobante_domicilio
 * @property string|null $comprobante_ine
 * @property string|null $comprobante_croquis
 * @property Carbon|null $fecha_alta
 * @property Carbon|null $fecha_nacimiento
 * @property string|null $email
 * @property string|null $fotografia
 * @property int|null $mueblerias_id
 * @property int|null $usuarios_id
 * @property int|null $direccion_id
 * @property string|null $observaciones
 * @property int|null $calificacion
 * @property int|null $acepta_promociones
 * 
 * @property Muebleria|null $muebleria
 * @property Usuario|null $usuario
 * @property Direccion|null $direccion
 * @property Collection|Venta[] $ventas
 *
 * @package App\Models
 */
class Cliente extends Model
{
	protected $table = 'clientes';
	protected $primaryKey = 'clientes_id';
	public $timestamps = false;

	protected $casts = [
		'fecha_alta' => 'datetime',
		'fecha_nacimiento' => 'datetime',
		'mueblerias_id' => 'int',
		'usuarios_id' => 'int',
		'direccion_id' => 'int',
		'calificacion' => 'int',
		'acepta_promociones' => 'int'
	];

	protected $fillable = [
		'nombres',
		'paterno',
		'materno',
		'tel_local',
		'celular',
		'comprobante_domicilio',
		'comprobante_ine',
		'comprobante_croquis',
		'fecha_alta',
		'fecha_nacimiento',
		'email',
		'fotografia',
		'mueblerias_id',
		'usuarios_id',
		'direccion_id',
		'observaciones',
		'calificacion',
		'acepta_promociones'
	];

	public function muebleria()
	{
		return $this->belongsTo(Muebleria::class, 'mueblerias_id');
	}

	public function usuario()
	{
		return $this->belongsTo(Usuario::class, 'usuarios_id');
	}

	public function direccion()
	{
		return $this->belongsTo(Direccion::class);
	}

	public function ventas()
	{
		return $this->hasMany(Venta::class, 'clientes_id');
	}
}
