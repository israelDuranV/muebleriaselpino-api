<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Fotografium
 * 
 * @property int $fotografia_id
 * @property string|null $ruta
 * 
 * @property Collection|Direccion[] $direccions
 * @property Collection|Muebleria[] $mueblerias
 *
 * @package App\Models
 */
class Fotografium extends Model
{
	protected $table = 'fotografia';
	protected $primaryKey = 'fotografia_id';
	public $timestamps = false;

	protected $fillable = [
		'ruta'
	];

	public function direccions()
	{
		return $this->hasMany(Direccion::class, 'fotografia_id');
	}

	public function mueblerias()
	{
		return $this->hasMany(Muebleria::class, 'fotografia_id');
	}
}
