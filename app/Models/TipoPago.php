<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TipoPago
 * 
 * @property int $tipo_pago_id
 * @property string|null $tipo
 * @property string|null $descripcion
 * 
 * @property Collection|Venta[] $ventas
 *
 * @package App\Models
 */
class TipoPago extends Model
{
	protected $table = 'tipo_pago';
	protected $primaryKey = 'tipo_pago_id';
	public $timestamps = false;

	protected $fillable = [
		'tipo',
		'descripcion'
	];

	public function ventas()
	{
		return $this->hasMany(Venta::class);
	}
}
