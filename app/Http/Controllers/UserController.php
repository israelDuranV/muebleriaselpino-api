<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = User::with(['roles', 'mueblerías']);

            // Búsqueda
            if ($request->has('search')) {
                $query->search($request->search);
            }

            // Filtrar por rol
            if ($request->has('role')) {
                $query->withRole($request->role);
            }

            // Filtrar por estado activo/inactivo
            if ($request->has('is_active')) {
                $query->where('is_active', $request->boolean('is_active'));
            }

            // Filtrar por mueblería
            if ($request->has('muebleria_id')) {
                $query->whereHas('mueblerías', function ($q) use ($request) {
                    $q->where('mueblerías.id', $request->muebleria_id);
                });
            }

            // Ordenamiento
            $sortBy = $request->input('sort_by', 'created_at');
            $sortOrder = $request->input('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            // Paginación
            $perPage = $request->input('per_page', 15);
            $users = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => UserResource::collection($users),
                'meta' => [
                    'total' => $users->total(),
                    'per_page' => $users->perPage(),
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener usuarios',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            // Crear usuario
            $userData = $request->only(['name', 'email', 'password', 'phone', 'address', 'is_active']);
            
            // Manejar imagen de perfil
            if ($request->hasFile('profile_image')) {
                $userData['profile_image'] = $this->uploadProfileImage($request->file('profile_image'));
            }

            $user = User::create($userData);

            // Asignar rol (si se proporciona, sino el modelo asigna rol por defecto ID 4)
            if ($request->has('role_id')) {
                $user->syncRoles([$request->role_id]);
            }

            // Asignar mueblerías
            if ($request->has('mueblerías')) {
                $this->syncMueblerías($user, $request->mueblerías, $request->primary_muebleria_id);
            }

            // Cargar relaciones para la respuesta
            $user->load(['roles', 'mueblerías']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Usuario creado exitosamente',
                'data' => new UserResource($user),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            // Eliminar imagen si se subió y ocurrió un error
            if (isset($userData['profile_image'])) {
                Storage::disk('public')->delete($userData['profile_image']);
            }

            return response()->json([
                'success' => false,
                'message' => 'Error al crear usuario',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $user = User::with(['roles', 'mueblerías', 'muebleriaPrincipal'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => new UserResource($user),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id): JsonResponse
    {
        DB::beginTransaction();

        try {
            $user = User::findOrFail($id);

            // Preparar datos para actualizar
            $userData = $request->only(['name', 'email', 'phone', 'address', 'is_active']);

            // Actualizar contraseña solo si se proporciona
            if ($request->filled('password')) {
                $userData['password'] = bcrypt($request->password);
            }

            // Manejar imagen de perfil
            if ($request->hasFile('profile_image')) {
                // Eliminar imagen anterior
                if ($user->profile_image) {
                    Storage::disk('public')->delete($user->profile_image);
                }
                $userData['profile_image'] = $this->uploadProfileImage($request->file('profile_image'));
            }

            // Opción para eliminar imagen
            if ($request->boolean('remove_profile_image')) {
                if ($user->profile_image) {
                    Storage::disk('public')->delete($user->profile_image);
                }
                $userData['profile_image'] = null;
            }

            // Actualizar usuario
            $user->update($userData);

            // Actualizar rol
            if ($request->has('role_id')) {
                $user->syncRoles([$request->role_id]);
            }

            // Actualizar mueblerías
            if ($request->has('mueblerías')) {
                $this->syncMueblerías($user, $request->mueblerías, $request->primary_muebleria_id);
            }

            // Cargar relaciones para la respuesta
            $user->load(['roles', 'mueblerías']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Usuario actualizado exitosamente',
                'data' => new UserResource($user),
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            // Eliminar nueva imagen si se subió y ocurrió un error
            if (isset($userData['profile_image']) && $userData['profile_image']) {
                Storage::disk('public')->delete($userData['profile_image']);
            }

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar usuario',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);

            // No permitir eliminar al usuario autenticado
            if (auth()->id() === $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'No puedes eliminar tu propio usuario',
                ], 403);
            }

            // La eliminación de la imagen se maneja en el evento deleting del modelo
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Usuario eliminado exitosamente',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar usuario',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Actualizar solo la imagen de perfil
     */
    public function updateProfileImage(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'profile_image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        try {
            $user = User::findOrFail($id);

            // Eliminar imagen anterior
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }

            // Subir nueva imagen
            $path = $this->uploadProfileImage($request->file('profile_image'));
            $user->update(['profile_image' => $path]);

            return response()->json([
                'success' => true,
                'message' => 'Imagen de perfil actualizada exitosamente',
                'data' => [
                    'profile_image' => $user->profile_image,
                    'profile_image_url' => $user->profile_image_url,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar imagen de perfil',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Eliminar imagen de perfil
     */
    public function deleteProfileImage(string $id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);

            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
                $user->update(['profile_image' => null]);

                return response()->json([
                    'success' => true,
                    'message' => 'Imagen de perfil eliminada exitosamente',
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'El usuario no tiene imagen de perfil',
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar imagen de perfil',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Asignar mueblerías al usuario
     */
    public function assignMueblerías(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'mueblerías' => ['required', 'array', 'min:1'],
            'mueblerías.*' => ['exists:mueblerías,id'],
            'primary_muebleria_id' => ['nullable', 'exists:mueblerías,id'],
        ]);

        DB::beginTransaction();

        try {
            $user = User::findOrFail($id);

            $this->syncMueblerías($user, $request->mueblerías, $request->primary_muebleria_id);

            $user->load('mueblerías');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Mueblerías asignadas exitosamente',
                'data' => new UserResource($user),
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error al asignar mueblerías',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Cambiar mueblería principal
     */
    public function setPrimaryMuebleria(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'muebleria_id' => ['required', 'exists:mueblerías,id'],
        ]);

        try {
            $user = User::findOrFail($id);

            // Verificar que el usuario tenga esa mueblería asignada
            if (!$user->mueblerías()->where('mueblerías.id', $request->muebleria_id)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'La mueblería debe estar asignada al usuario primero',
                ], 400);
            }

            $user->setPrimaryMuebleria($request->muebleria_id);
            $user->load(['mueblerías', 'muebleriaPrincipal']);

            return response()->json([
                'success' => true,
                'message' => 'Mueblería principal actualizada exitosamente',
                'data' => new UserResource($user),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al establecer mueblería principal',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Subir imagen de perfil
     */
    private function uploadProfileImage($file): string
    {
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        return $file->storeAs('profile_images', $filename, 'public');
    }

    /**
     * Sincronizar mueblerías del usuario
     */
    private function syncMueblerías(User $user, array $muebleriaIds, ?int $primaryMuebleriaId = null): void
    {
        // Preparar datos para sync con pivot
        $syncData = [];
        foreach ($muebleriaIds as $muebleriaId) {
            $syncData[$muebleriaId] = [
                'is_primary' => $primaryMuebleriaId && $primaryMuebleriaId == $muebleriaId,
            ];
        }

        $user->mueblerías()->sync($syncData);
    }
}