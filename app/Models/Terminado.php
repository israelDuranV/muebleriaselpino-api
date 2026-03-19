<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Terminado extends Model
{
    use HasFactory;

    protected $table = 'terminado';
    protected $primaryKey = 'terminado_id';
    public $timestamps = false;

    protected $fillable = [
        'terminado',
        'descripcion',
    ];

    /**
     * Relación con Muebles
     */
    public function muebles()
    {
        return $this->hasMany(Mueble::class, 'terminado_id', 'terminado_id');
    }
}
