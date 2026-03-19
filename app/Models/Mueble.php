<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Mueble extends Model
{
    use HasFactory;

    /**
     * El nombre de la tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'muebles';

    /**
     * La clave primaria de la tabla.
     *
     * @var string
     */
    protected $primaryKey = 'muebles_id';

    /**
     * Indica si el modelo debe usar timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'terminado_id',
        'stock',
        'sincera',
        'observacion',
        'nombre',
        'materiales_id',
        'fotografia',
        'encerado',
        'departamento_id',
        'costo',
        'barniz',
    ];
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where('muebles_id', $value)->firstOrFail();
    }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'stock' => 'integer',
            'sincera' => 'integer',
            'encerado' => 'integer',
            'costo' => 'integer',
            'barniz' => 'integer',
            'terminado_id' => 'integer',
            'materiales_id' => 'integer',
            'departamento_id' => 'integer',
        ];
    }

    // ==================== RELACIONES ====================

    /**
     * Relación con Terminado
     */
    public function inventarios()
    {
        return $this->hasMany(InventarioGeneral::class, 'muebles_id', 'muebles_id');
    }

    public function terminado()
    {
        return $this->belongsTo(Terminado::class, 'terminado_id', 'terminado_id');
    }

    /**
     * Relación con Material
     */
    public function material()
    {
        return $this->belongsTo(Material::class, 'materiales_id', 'materiales_id');
    }

    /**
     * Relación con Departamento
     */
    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'departamento_id', 'departamento_id');
    }

    /**
     * Relación con Fotos del Mueble
     */
    public function fotos()
    {
        return $this->hasMany(MuebleFoto::class, 'muebles_id', 'muebles_id');
    }

    /**
     * Relación con Pedidos
     */
    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'muebles_id', 'muebles_id');
    }

    // ==================== MÉTODOS DEL STORED PROCEDURE ====================

    /**
     * GETDEPARTAMENTO - Obtener muebles por departamento
     *
     * @param int $departamentoId
     * @return \Illuminate\Support\Collection
     */
    public static function getMueblesByDepartamento(int $departamentoId)
    {
        return DB::select('CALL sp_consultaMuebles(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            'GETDEPARTAMENTO',      // _accion
            null,                   // _terminado
            null,                   // _stock
            null,                   // _sincera
            null,                   // _observacion
            null,                   // _nombre
            null,                   // _material
            null,                   // _fotografia
            null,                   // _encerado
            $departamentoId,        // _departamento
            null,                   // _costo
            null,                   // _barniz
            null                    // _id
        ]);
    }

    /**
     * GETMUEBLE - Obtener un mueble específico
     *
     * @param int $muebleId
     * @return object|null
     */
    public static function getMuebleById(int $muebleId)
    {
        $result = DB::select('CALL sp_consultaMuebles(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            'GETMUEBLE',            // _accion
            null, null, null, null, null, null, null, null, null, null, null,
            $muebleId               // _id
        ]);

        return $result[0] ?? null;
    }
    public static function getMuebleByName(String $search)
    {
        $result = DB::select('CALL sp_consultaMuebles(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            'SEARCHMUEBLE',            // _accion
            null, null, null, null, $search, null, null, null, null, null, null,
            0               // _id
        ]);

        return $result[0] ?? null;
    }
    /**
     * GETMUEBLES - Obtener todos los muebles
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getAllMuebles()
    {
        return DB::select('CALL sp_consultaMuebles(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            'GETMUEBLES',           // _accion
            null, null, null, null, null, null, null, null, null, null, null, null
        ]);
    }

    /**
     * INSERTMUEBLES - Crear un nuevo mueble
     *
     * @param array $data
     * @return bool
     */
    public static function insertMueble(array $data)
    {
        try {
            DB::statement('CALL sp_consultaMuebles(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                'INSERTMUEBLES',                    // _accion
                $data['terminado_id'],              // _terminado
                $data['stock'] ?? 0,                // _stock
                $data['sincera'] ?? 0,              // _sincera
                $data['observacion'] ?? '',         // _observacion
                $data['nombre'],                    // _nombre
                $data['materiales_id'],             // _material
                $data['fotografia'] ?? '',          // _fotografia
                $data['encerado'] ?? 0,             // _encerado
                $data['departamento_id'],           // _departamento
                $data['costo'] ?? 0,                // _costo
                $data['barniz'] ?? 0,               // _barniz
                null                                // _id
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Error en insertMueble: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * UPDATE - Actualizar un mueble
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public static function updateMueble(int $id, array $data)
    {
        try {
            DB::statement('CALL sp_consultaMuebles(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                'UPDATE',                           // _accion
                $data['terminado_id'] ?? 0,         // _terminado
                $data['stock'] ?? 0,                // _stock
                $data['sincera'] ?? 0,              // _sincera
                $data['observacion'] ?? '',         // _observacion
                $data['nombre'] ?? '',              // _nombre
                $data['materiales_id'] ?? 0,        // _material
                $data['fotografia'] ?? '',          // _fotografia
                $data['encerado'] ?? 0,             // _encerado
                $data['departamento_id'] ?? 0,      // _departamento
                $data['costo'] ?? 0,                // _costo
                $data['barniz'] ?? 0,               // _barniz
                $id                                 // _id
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Error en updateMueble: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * DELETE - Eliminar un mueble
     *
     * @param int $muebleId
     * @return bool
     */
    public static function deleteMueble(int $muebleId)
    {
        try {
            DB::statement('CALL sp_consultaMuebles(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                'DELETE',               // _accion
                null, null, null, null, null, null, null, null, null, null, null,
                $muebleId               // _id
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Error en deleteMueble: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * DELETEFOTO - Eliminar una foto del mueble
     *
     * @param int $muebleId
     * @param string $fotoUrl
     * @return bool
     */
    public static function deleteFoto(int $muebleId, string $fotoUrl)
    {
        try {
            DB::statement('CALL sp_consultaMuebles(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                'DELETEFOTO',           // _accion
                null,                   // _terminado
                null,                   // _stock
                null,                   // _sincera
                null,                   // _observacion
                null,                   // _nombre
                null,                   // _material
                $fotoUrl,               // _fotografia
                null,                   // _encerado
                null,                   // _departamento
                null,                   // _costo
                null,                   // _barniz
                $muebleId               // _id
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Error en deleteFoto: ' . $e->getMessage());
            return false;
        }
    }

    // ==================== MÉTODOS AUXILIARES ====================

    /**
     * Obtener array de URLs de fotos
     *
     * @return array
     */
    public function getFotosArrayAttribute()
    {
        if (empty($this->fotografia)) {
            return [];
        }

        return array_filter(array_map('trim', explode(',', $this->fotografia)));
    }

    /**
     * Agregar una nueva foto al mueble
     *
     * @param string $fotoUrl
     * @return bool
     */
    public function addFoto(string $fotoUrl)
    {
        return self::updateMueble($this->muebles_id, [
            'fotografia' => $fotoUrl
        ]);
    }

    /**
     * Verificar si el mueble tiene stock disponible
     *
     * @return bool
     */
    public function hasStock()
    {
        return $this->stock > 0;
    }

    // ==================== SCOPES ====================

    /**
     * Scope para muebles con stock
     */
    public function scopeConStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    /**
     * Scope para filtrar por departamento
     */
    public function scopeByDepartamento($query, $departamentoId)
    {
        return $query->where('departamento_id', $departamentoId);
    }

    /**
     * Scope para filtrar por material
     */
    public function scopeByMaterial($query, $materialId)
    {
        return $query->where('materiales_id', $materialId);
    }

    /**
     * Scope para filtrar por terminado
     */
    public function scopeByTerminado($query, $terminadoId)
    {
        return $query->where('terminado_id', $terminadoId);
    }

    /**
     * Scope para buscar por nombre
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('nombre', 'like', "%{$search}%");
        }
        return $query;
    }

    /**
     * Scope para ordenar por costo
     */
    public function scopeOrderByCosto($query, $direction = 'asc')
    {
        return $query->orderBy('costo', $direction);
    }
}