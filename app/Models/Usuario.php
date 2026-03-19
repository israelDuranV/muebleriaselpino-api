<?php 

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Usuario extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'usuarios_id';
    public $timestamps = false;

    protected $fillable = [
        'usuario',
        'secret',
        'nombres',
        'paterno',
        'materno',
        'fecha_nacimiento',
        'telefono',
        'sueldo',
        'nss',
        'curp',
        'cartilla',
        'licencia',
        'rfc',
        'estudios',
        'fecha_alta',
        'fotografia',
        'email',
        'mueblerias',
        'estatus',
        'direccion_id',
        'roles_id',
        'alias',
        'darkmode',
        'sobremi',
        'comentario'
    ];

    protected $hidden = [
        'secret'
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'fecha_alta' => 'date',
        'sueldo' => 'integer',
        'telefono' => 'integer',
        'estatus' => 'boolean',
        'darkmode' => 'boolean',
        'roles_id' => 'integer'
    ];

    // Relaciones
    public function direccion()
    {
        return $this->belongsTo(Direccion::class, 'direccion_id');
    }

    public function rol()
    {
        return $this->belongsTo(Role::class, 'roles_id');
    }

    public function asignacionesMueblerias()
    {
        return $this->hasMany(AsignacionMuebleria::class, 'usuario', 'usuarios_id');
    }

    public function muebleriasAsignadas()
    {
        return $this->belongsToMany(
            Muebleria::class,
            'asignacion_muebleria',
            'usuario',
            'muebleria',
            'usuarios_id',
            'mueblerias_id'
        )->wherePivot('estatus', 1);
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class, 'usuarios_id');
    }

    /**
     * Guardar un nuevo usuario con dirección y mueblerías
     */
    public static function guardarUsuario(array $datos)
    {
        // Preparar el string de mueblerías (IDs separados por coma)
        $muebleriasStr = is_array($datos['mueblerias']) 
            ? implode(',', $datos['mueblerias'])
            : $datos['mueblerias'];

        DB::statement('CALL sp_consultaUsuarios(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            'SAVE',
            $datos['usuario'],
            $datos['secret'], // Ya debe venir hasheado
            $datos['nombres'],
            $datos['paterno'] ?? null,
            $datos['materno'] ?? null,
            $datos['telefono'] ?? null,
            $datos['fecha_nacimiento'] ?? null,
            $datos['sueldo'] ?? null,
            $datos['nss'] ?? null,
            $datos['curp'] ?? null,
            $datos['cartilla'] ?? null,
            $datos['licencia'] ?? null,
            $datos['rfc'] ?? null,
            $datos['estudios'] ?? null,
            $datos['fecha_alta'] ?? now()->format('Y-m-d'),
            $datos['fotografia'] ?? null,
            $datos['email'] ?? null,
            $muebleriasStr,
            $datos['calle'] ?? null,
            $datos['numero'] ?? null,
            $datos['colonia'] ?? null,
            $datos['municipio'] ?? null,
            $datos['estado'] ?? null,
            $datos['cp'] ?? null,
            $datos['longitud'] ?? null,
            $datos['latitud'] ?? null,
            $datos['rol_id'],
            null, // id_direccion (no usado en SAVE)
            null, // alias
            null, // darkmode
            null, // sobremi
            null  // id
        ]);

        return true;
    }

    /**
     * Actualizar usuario existente
     */
    public static function actualizarUsuario($id, array $datos)
    {
        // Preparar el string de mueblerías
        $muebleriasStr = is_array($datos['mueblerias']) 
            ? implode(',', $datos['mueblerias'])
            : $datos['mueblerias'];

        DB::statement('CALL sp_consultaUsuarios(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            'UPDATE',
            $datos['usuario'],
            null, // secret (no se actualiza aquí)
            $datos['nombres'],
            $datos['paterno'] ?? null,
            $datos['materno'] ?? null,
            $datos['telefono'] ?? null,
            $datos['fecha_nacimiento'] ?? null,
            $datos['sueldo'] ?? null,
            $datos['nss'] ?? null,
            $datos['curp'] ?? null,
            $datos['cartilla'] ?? null,
            $datos['licencia'] ?? null,
            $datos['rfc'] ?? null,
            $datos['estudios'] ?? null,
            $datos['fecha_alta'] ?? null,
            $datos['fotografia'] ?? null,
            $datos['email'] ?? null,
            $muebleriasStr,
            $datos['calle'] ?? null,
            $datos['numero'] ?? null,
            $datos['colonia'] ?? null,
            $datos['municipio'] ?? null,
            $datos['estado'] ?? null,
            $datos['cp'] ?? null,
            null, // longitud (no se actualiza)
            null, // latitud (no se actualiza)
            null, // perfil (no se actualiza)
            $datos['direccion_id'],
            null, // alias
            null, // darkmode
            null, // sobremi
            $id
        ]);

        return true;
    }

    /**
     * Obtener todos los usuarios activos con sus relaciones
     */
    public static function obtenerUsuarios()
    {
        return DB::select('CALL sp_consultaUsuarios(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            'GET',
            null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null
        ]);
    }

    /**
     * Obtener un usuario específico con toda su información
     */
    public static function obtenerUsuarioPorId($id)
    {
        $resultado = DB::select('CALL sp_consultaUsuarios(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            'GETUSUARIO',
            null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null,
            $id
        ]);

        return $resultado[0] ?? null;
    }

    /**
     * Actualizar perfil del usuario (alias, email, darkmode, sobremi)
     */
    public static function actualizarPerfil($id, array $datos)
    {
        DB::statement('CALL sp_consultaUsuarios(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            'SAVEPERFIL',
            null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null,
            $datos['email'] ?? null,
            null, null, null, null, null, null, null, null, null, null, null,
            $datos['alias'] ?? null,
            $datos['darkmode'] ?? 0,
            $datos['sobremi'] ?? null,
            $id
        ]);

        return true;
    }

    /**
     * Cambiar contraseña de usuario
     */
    public static function cambiarPassword($usuario, $nuevaPassword)
    {
        // Hashear la contraseña
        $passwordHash = Hash::make($nuevaPassword);

        DB::statement('CALL sp_consultaUsuarios(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            'CHANGEPASSWORD',
            $usuario,
            $passwordHash,
            null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null
        ]);

        return true;
    }

    /**
     * Desactivar usuario (soft delete)
     */
    public static function eliminarUsuario($id)
    {
        DB::statement('CALL sp_consultaUsuarios(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            'DELETE',
            null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null,
            $id
        ]);

        return true;
    }

    /**
     * Scope para usuarios activos
     */
    public function scopeActivos($query)
    {
        return $query->where('estatus', 1);
    }

    /**
     * Scope para usuarios por rol
     */
    public function scopePorRol($query, $rolId)
    {
        return $query->where('roles_id', $rolId);
    }

    /**
     * Scope para usuarios con dark mode activado
     */
    public function scopeConDarkMode($query)
    {
        return $query->where('darkmode', 1);
    }

    /**
     * Obtener nombre completo del usuario
     */
    public function getNombreCompletoAttribute()
    {
        return trim("{$this->nombres} {$this->paterno} {$this->materno}");
    }

    /**
     * Verificar si el usuario tiene acceso a una mueblería
     */
    public function tieneAccesoMuebleria($muebleríaId)
    {
        return $this->muebleriasAsignadas()
            ->where('mueblerias.mueblerias_id', $muebleríaId)
            ->exists();
    }

    /**
     * Obtener IDs de mueblerías asignadas
     */
    public function getMuebleriasIdsAttribute()
    {
        return $this->muebleriasAsignadas->pluck('mueblerias_id')->toArray();
    }

    /**
     * Verificar contraseña
     */
    public function verificarPassword($password)
    {
        return Hash::check($password, $this->secret);
    }
}
