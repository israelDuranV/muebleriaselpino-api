<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class InventarioGralTmp
 * 
 * @property int|null $inventarios_id
 * @property int|null $muebles_id
 * @property int|null $pedido
 * @property int|null $estatus
 * @property int|null $usuario_id
 * @property int|null $procedencia
 * @property int|null $muebleria_id
 * @property int $cantidad
 * @property string|null $mueble
 * @property string|null $descripcion
 * @property string|null $departamento
 * @property string $tipo
 * @property string|null $usuario
 *
 * @package App\Models
 */
class InventarioGralTmp extends Model
{
	protected $table = 'inventario_gral_tmp';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'inventarios_id' => 'int',
		'muebles_id' => 'int',
		'pedido' => 'int',
		'estatus' => 'int',
		'usuario_id' => 'int',
		'procedencia' => 'int',
		'muebleria_id' => 'int',
		'cantidad' => 'int'
	];

	protected $fillable = [
		'inventarios_id',
		'muebles_id',
		'pedido',
		'estatus',
		'usuario_id',
		'procedencia',
		'muebleria_id',
		'cantidad',
		'mueble',
		'descripcion',
		'departamento',
		'tipo',
		'usuario'
	];
}
