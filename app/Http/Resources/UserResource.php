<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'is_active' => $this->is_active,
            
            // Imagen de perfil
            'profile_image' => $this->profile_image,
            'profile_image_url' => $this->profile_image_url,
            'initials' => $this->initials,
            
            // Roles y permisos
            'roles' => $this->roles->pluck('name'),
            'role_name' => $this->role_name,
            'permissions' => $this->when(
                $request->input('include_permissions'),
                $this->getAllPermissions()->pluck('name')
            ),
            
            // Mueblerías
            'mueblerías' => MuebleriaResource::collection($this->whenLoaded('mueblerías')),
            'muebleria_principal' => new MuebleriaResource($this->whenLoaded('muebleriaPrincipal')),
            'mueblerías_count' => $this->when(
                $this->relationLoaded('mueblerías'),
                $this->mueblerías->count()
            ),
            
            // Timestamps
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            'email_verified_at' => $this->email_verified_at?->toISOString(),
        ];
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @return array<string, mixed>
     */
    public function with(Request $request): array
    {
        return [
            'meta' => [
                'version' => '1.0',
            ],
        ];
    }
}