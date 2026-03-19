<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Departamento extends Model
{
    use HasFactory;

    protected $table = 'departamentos';
    protected $primaryKey = 'departamento_id';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'descripcion',
    ];

    /**
     * Relación con Muebles
     */
    public function muebles()
    {
        return $this->hasMany(Mueble::class, 'departamento_id', 'departamento_id');
    }
}
