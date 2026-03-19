<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Bono
 * 
 * @property int $bonos_id
 * @property int|null $usuarios_id
 * @property Carbon|null $fecha_corte
 * @property Carbon|null $fecha_final
 * @property int|null $limite_tiempo
 * 
 * @property Usuario|null $usuario
 *
 * @package App\Models
 */
class Bono extends Model
{
	protected $table = 'bonos';
	protected $primaryKey = 'bonos_id';
	public $timestamps = false;

	protected $casts = [
		'usuarios_id' => 'int',
		'fecha_corte' => 'datetime',
		'fecha_final' => 'datetime',
		'limite_tiempo' => 'int'
	];

	protected $fillable = [
		'usuarios_id',
		'fecha_corte',
		'fecha_final',
		'limite_tiempo'
	];

	public function usuario()
	{
		return $this->belongsTo(Usuario::class, 'usuarios_id');
	}
}
