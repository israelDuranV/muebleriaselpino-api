<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Icono
 * 
 * @property int $Id
 * @property string|null $clase
 * @property string|null $descripcion
 *
 * @package App\Models
 */
class Icono extends Model
{
	protected $table = 'iconos';
	protected $primaryKey = 'Id';
	public $timestamps = false;

	protected $fillable = [
		'clase',
		'descripcion'
	];
}
