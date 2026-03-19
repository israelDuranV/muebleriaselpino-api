<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
/**
 * Class Muebleria
 * 
 * @property int $mueblerias_id
 * @property string|null $nombre
 * @property string|null $tipo
 * @property int|null $direccion_id
 * @property int|null $fotografia_id
 * @property int|null $estatus
 * 
 * @property Direccion|null $direccion
 * @property Fotografium|null $fotografium
 * @property Collection|Cliente[] $clientes
 * @property Collection|Inventario[] $inventarios
 * @property Collection|Venta[] $ventas
 *
 * @package App\Models
 */
class Muebleria extends Model
{
	protected $table = 'mueblerias';
	protected $primaryKey = 'mueblerias_id';
	public $timestamps = false;

	protected $casts = [
		'direccion_id' => 'int',
		'fotografia_id' => 'int',
		'estatus' => 'int'
	];

	protected $fillable = [
		'nombre',
		'tipo',
		'direccion_id',
		'fotografia_id',
		'estatus'
	];
	public static function asignadas($id){
		$query = "CALL sp_consultaMueblerias(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $params = ['ASIGNADAS','',$id,0,'',0,'','','',0,null,null,'','',0, 0];
        $result = DB::select($query, $params);
		
        return collect($result);
	}
	public function inventarios()
    {
        return $this->hasMany(InventarioGeneral::class, 'muebleria_id', 'mueblerias_id');
    }
	public function direccion()
	{
		return $this->belongsTo(Direccion::class);
	}

	public function fotografium()
	{
		return $this->belongsTo(Fotografium::class, 'fotografia_id');
	}

	public function clientes()
	{
		return $this->hasMany(Cliente::class, 'mueblerias_id');
	}

	public function ventas()
	{
		return $this->hasMany(Venta::class, 'mueblerias_id');
	}
}
