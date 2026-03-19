<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Permiso
 * 
 * @property int $permisos_id
 * @property int|null $roles_id
 * @property int|null $modulos_id
 * @property int|null $editar
 * @property int|null $ver
 * @property int $insertar
 * @property int $eliminar
 *
 * @package App\Models
 */
class Permiso extends Model
{
	protected $table = 'permisos';
	protected $primaryKey = 'permisos_id';
	public $timestamps = false;

	protected $casts = [
		'roles_id' => 'int',
		'modulos_id' => 'int',
		'editar' => 'int',
		'ver' => 'int',
		'insertar' => 'int',
		'eliminar' => 'int'
	];

	protected $fillable = [
		'roles_id',
		'modulos_id',
		'editar',
		'ver',
		'insertar',
		'eliminar'
	];
}
