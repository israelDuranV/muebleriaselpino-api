<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CodigosPostale
 * 
 * @property int $id
 * @property int|null $codigoPostal
 * @property string|null $estado
 * @property string|null $municipio
 * @property string|null $ciudad
 * @property string|null $tipoAsentamiento
 * @property string|null $asentamiento
 * @property int|null $claveOficina
 *
 * @package App\Models
 */
class CodigosPostale extends Model
{
	protected $table = 'codigos_postales';
	public $timestamps = false;

	protected $casts = [
		'codigoPostal' => 'int',
		'claveOficina' => 'int'
	];

	protected $fillable = [
		'codigoPostal',
		'estado',
		'municipio',
		'ciudad',
		'tipoAsentamiento',
		'asentamiento',
		'claveOficina'
	];
}
