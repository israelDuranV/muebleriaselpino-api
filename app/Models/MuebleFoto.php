<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MuebleFoto extends Model
{
    use HasFactory;

    protected $table = 'mueble_fotos';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'muebles_id',
        'url',
        'orden',
        'descripcion',
    ];

    protected function casts(): array
    {
        return [
            'muebles_id' => 'integer',
            'orden' => 'integer',
        ];
    }

    /**
     * Relación con Mueble
     */
    public function mueble()
    {
        return $this->belongsTo(Mueble::class, 'muebles_id', 'muebles_id');
    }

    /**
     * Obtener URL completa de la foto
     */
    public function getUrlCompletaAttribute()
    {
        return Storage::disk('public')->url($this->url);
    }

    /**
     * Scope para ordenar por campo orden
     */
    public function scopeOrdenado($query)
    {
        return $query->orderBy('orden', 'asc');
    }
}
