<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Modulo
 * 
 * @property int $modulos_id
 * @property int $menu_id
 * @property string|null $modulo
 * @property string $icono
 * @property string|null $label
 * @property string $url
 * @property string|null $description
 *
 * @package App\Models
 */
class Modulo extends Model
{
	protected $table = 'modulos';
	protected $primaryKey = 'modulos_id';
	public $timestamps = false;

	protected $casts = [
		'menu_id' => 'int'
	];

	protected $fillable = [
		'menu_id',
		'modulo',
		'icono',
		'label',
		'url',
		'description'
	];
}
