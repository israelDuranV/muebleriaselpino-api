<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;
class User extends Authenticatable
{
	use HasApiTokens, HasFactory, Notifiable, HasRoles;
	protected $table = 'users';
    protected $primaryKey = 'id';

	protected $fillable = [
        'name',
        'email',
        'password',
        'profile_image',
        'is_active',
        'phone',
        'address',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Boot del modelo para eventos
     */
    protected static function boot()
    {
        parent::boot();

        // Asignar rol por defecto al crear usuario
        static::created(function ($user) {
            if (!$user->roles()->exists()) {
                $user->assignRole(4); // Rol de usuario por defecto (ID 4)
            }
        });

        // Eliminar imagen al eliminar usuario
        static::deleting(function ($user) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
        });
    }

    /**
     * Relación muchos a muchos con Mueblería
     */
    public function mueblerías()
    {
        return $this->belongsToMany(Muebleria::class, 'user_muebleria')
            ->withPivot('is_primary')
            ->withTimestamps();
    }

    /**
     * Obtener la mueblería principal del usuario
     */
    public function muebleriaPrincipal()
    {
        return $this->belongsToMany(Muebleria::class, 'user_muebleria')
            ->wherePivot('is_primary', true)
            ->withTimestamps();
    }

    /**
     * Scope para usuarios activos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para buscar usuarios
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        return $query;
    }

    /**
     * Scope para filtrar por rol
     */
    public function scopeWithRole($query, $roleName)
    {
        return $query->whereHas('roles', function ($q) use ($roleName) {
            $q->where('name', $roleName);
        });
    }

    /**
     * Obtener URL completa de la imagen de perfil
     */
    public function getProfileImageUrlAttribute()
    {
        if ($this->profile_image) {
            return Storage::disk('public')->url($this->profile_image);
        }
        
        // Retornar imagen por defecto o avatar con iniciales
        return $this->getDefaultAvatar();
    }

    /**
     * Obtener avatar por defecto (puede ser URL de servicio como ui-avatars.com)
     */
    public function getDefaultAvatar()
    {
        $name = urlencode($this->name);
        return "https://ui-avatars.com/api/?name={$name}&size=200&background=667eea&color=fff";
    }

    /**
     * Obtener iniciales del usuario
     */
    public function getInitialsAttribute()
    {
        $words = explode(' ', $this->name);
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        return strtoupper(substr($this->name, 0, 2));
    }

    /**
     * Verificar si el usuario tiene mueblerías asignadas
     */
    public function hasMueblerías()
    {
        return $this->mueblerías()->exists();
    }
    public function mueblerias()
    {
        return $this->belongsToMany(Muebleria::class, 'user_muebleria', 'user_id', 'mueblerias_id');
    }


    /**
     * Asignar mueblería principal
     */
    public function setPrimaryMuebleria($muebleriaId)
    {
        // Primero, remover el flag de principal de todas las mueblerías
        $this->mueblerías()->updateExistingPivot(
            $this->mueblerías()->pluck('mueblerías.id')->toArray(),
            ['is_primary' => false]
        );

        // Luego, marcar la nueva como principal
        if ($this->mueblerías()->where('mueblerías.id', $muebleriaId)->exists()) {
            $this->mueblerías()->updateExistingPivot($muebleriaId, ['is_primary' => true]);
        }
    }

    /**
     * Obtener el nombre completo del rol principal
     */
    public function getRoleNameAttribute()
    {
        return $this->roles->first()?->name ?? 'Sin rol';
    }

    /**
     * Serialización personalizada para API
     */
    public function toArray()
    {
        $array = parent::toArray();
        $array['profile_image_url'] = $this->profile_image_url;
        $array['initials'] = $this->initials;
        $array['role_name'] = $this->role_name;
        return $array;
    }
}
