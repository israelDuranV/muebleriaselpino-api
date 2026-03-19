<?php 
namespace App\Models;
use App\Models\Usuario;
use App\Models\Muebleria;
use Illuminate\Database\Eloquent\Model;

class AsignacionMuebleria extends Model
{
    protected $table = 'asignacion_muebleria';
    protected $primaryKey = 'asignacion_muebleria_id';
    public $timestamps = false;

    protected $fillable = [
        'muebleria',
        'usuario',
        'estatus'
    ];

    protected $casts = [
        'estatus' => 'boolean'
    ];

    /**
     * Relación con Usuario
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario', 'usuarios_id');
    }

    /**
     * Relación con Mueblería
     */
    public function muebleria()
    {
        return $this->belongsTo(Muebleria::class, 'muebleria', 'mueblerias_id');
    }

    /**
     * Scope para asignaciones activas
     */
    public function scopeActivas($query)
    {
        return $query->where('estatus', 1);
    }
}