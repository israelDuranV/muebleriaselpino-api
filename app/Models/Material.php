<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $table = 'materiales';
    protected $primaryKey = 'materiales_id';
    public $timestamps = false;

    protected $fillable = [
        'material',
        'descripcion',
    ];

    /**
     * Relación con Muebles
     */
    public function muebles()
    {
        return $this->hasMany(Mueble::class, 'materiales_id', 'materiales_id');
    }
}
