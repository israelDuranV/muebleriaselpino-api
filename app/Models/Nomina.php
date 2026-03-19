<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Nomina
 * 
 * @property int $nominas_id
 * @property int|null $usuarios_id
 * @property Carbon|null $fecha_corte
 * @property Carbon|null $fecha_final
 * 
 * @property Usuario|null $usuario
 *
 * @package App\Models
 */
class Nomina extends Model
{
	protected $table = 'nominas';
	protected $primaryKey = 'nominas_id';
	public $timestamps = false;

	protected $casts = [
		'usuarios_id' => 'int',
		'fecha_corte' => 'datetime',
		'fecha_final' => 'datetime'
	];

	protected $fillable = [
		'usuarios_id',
		'fecha_corte',
		'fecha_final'
	];

	public function usuario()
	{
		return $this->belongsTo(Usuario::class, 'usuarios_id');
	}
}
