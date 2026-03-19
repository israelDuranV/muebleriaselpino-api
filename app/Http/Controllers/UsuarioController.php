<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class UsuarioController extends Controller
{
    /**
     * Listar todos los usuarios activos
     */
    public function index()
    {
        try {
            $usuarios = Usuario::obtenerUsuarios();
            
            return response()->json([
                'success' => true,
                'data' => $usuarios,
                'total' => count($usuarios)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener usuarios: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener un usuario específico
     */
    public function show($id)
    {
        try {
            $usuario = Usuario::obtenerUsuarioPorId($id);
            
            if (!$usuario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $usuario
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear un nuevo usuario
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Datos de autenticación
            'usuario' => 'required|string|max:30|unique:usuarios,usuario',
            'password' => ['required', Password::min(8)->mixedCase()->numbers()],
            'email' => 'required|email|max:50|unique:usuarios,email',
            'rol_id' => 'required|integer|exists:roles,roles_id',
            
            // Datos personales
            'nombres' => 'required|string|max:40',
            'paterno' => 'nullable|string|max:20',
            'materno' => 'nullable|string|max:20',
            'telefono' => 'nullable|integer',
            'fecha_nacimiento' => 'nullable|date',
            'fotografia' => 'nullable|string|max:100',
            
            // Datos laborales
            'sueldo' => 'nullable|integer|min:0',
            'fecha_alta' => 'nullable|date',
            'estudios' => 'nullable|string|max:60',
            'mueblerias' => 'required|array|min:1',
            'mueblerias.*' => 'integer|exists:mueblerias,mueblerias_id',
            
            // Documentos
            'nss' => 'nullable|string|max:15',
            'curp' => 'nullable|string|max:30',
            'cartilla' => 'nullable|string|max:9',
            'licencia' => 'nullable|string|max:50',
            'rfc' => 'nullable|string|max:16',
            
            // Dirección
            'calle' => 'required|string|max:100',
            'numero' => 'nullable|integer',
            'colonia' => 'required|string|max:100',
            'municipio' => 'required|string|max:100',
            'estado' => 'required|string|max:100',
            'cp' => 'required|integer',
            'longitud' => 'nullable|numeric',
            'latitud' => 'nullable|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $datos = $request->all();
            $datos['secret'] = Hash::make($request->password);
            
            Usuario::guardarUsuario($datos);

            return response()->json([
                'success' => true,
                'message' => 'Usuario creado exitosamente'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar usuario existente
     */
    public function update(Request $request, $id)
    {
        // Verificar que el usuario existe
        $usuarioExiste = Usuario::obtenerUsuarioPorId($id);
        if (!$usuarioExiste) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'usuario' => 'required|string|max:30|unique:usuarios,usuario,' . $id . ',usuarios_id',
            'email' => 'required|email|max:50|unique:usuarios,email,' . $id . ',usuarios_id',
            
            'nombres' => 'required|string|max:40',
            'paterno' => 'nullable|string|max:20',
            'materno' => 'nullable|string|max:20',
            'telefono' => 'nullable|integer',
            'fecha_nacimiento' => 'nullable|date',
            'fotografia' => 'nullable|string|max:100',
            
            'sueldo' => 'nullable|integer|min:0',
            'fecha_alta' => 'nullable|date',
            'estudios' => 'nullable|string|max:60',
            'mueblerias' => 'required|array|min:1',
            'mueblerias.*' => 'integer|exists:mueblerias,mueblerias_id',
            
            'nss' => 'nullable|string|max:15',
            'curp' => 'nullable|string|max:30',
            'cartilla' => 'nullable|string|max:9',
            'licencia' => 'nullable|string|max:50',
            'rfc' => 'nullable|string|max:16',
            
            'calle' => 'required|string|max:100',
            'numero' => 'nullable|integer',
            'colonia' => 'required|string|max:100',
            'municipio' => 'required|string|max:100',
            'estado' => 'required|string|max:100',
            'cp' => 'required|integer',
            'direccion_id' => 'required|integer|exists:direccion,direccion_id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            Usuario::actualizarUsuario($id, $request->all());

            return response()->json([
                'success' => true,
                'message' => 'Usuario actualizado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar perfil del usuario (configuraciones personales)
     */
    public function actualizarPerfil(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'alias' => 'nullable|string|max:50',
            'email' => 'required|email|max:50',
            'darkmode' => 'nullable|boolean',
            'sobremi' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            Usuario::actualizarPerfil($id, $request->all());

            return response()->json([
                'success' => true,
                'message' => 'Perfil actualizado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar perfil: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cambiar contraseña
     */
    public function cambiarPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'usuario' => 'required|string|exists:usuarios,usuario',
            'password_actual' => 'required|string',
            'password_nuevo' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Verificar contraseña actual
            $usuario = Usuario::where('usuario', $request->usuario)->first();
            
            if (!$usuario || !$usuario->verificarPassword($request->password_actual)) {
                return response()->json([
                    'success' => false,
                    'message' => 'La contraseña actual es incorrecta'
                ], 401);
            }

            Usuario::cambiarPassword($request->usuario, $request->password_nuevo);

            return response()->json([
                'success' => true,
                'message' => 'Contraseña actualizada exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar contraseña: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Desactivar usuario (soft delete)
     */
    public function destroy($id)
    {
        try {
            // Verificar que existe
            $usuario = Usuario::obtenerUsuarioPorId($id);
            if (!$usuario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no encontrado'
                ], 404);
            }

            Usuario::eliminarUsuario($id);

            return response()->json([
                'success' => true,
                'message' => 'Usuario desactivado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener usuarios por rol
     */
    public function porRol($rolId)
    {
        try {
            $usuarios = Usuario::activos()->porRol($rolId)->get();

            return response()->json([
                'success' => true,
                'data' => $usuarios,
                'total' => $usuarios->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener usuarios: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verificar acceso a mueblería
     */
    public function verificarAccesoMuebleria($usuarioId, $muebleriaId)
    {
        try {
            $usuario = Usuario::find($usuarioId);
            
            if (!$usuario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no encontrado'
                ], 404);
            }

            $tieneAcceso = $usuario->tieneAccesoMuebleria($muebleriaId);

            return response()->json([
                'success' => true,
                'tiene_acceso' => $tieneAcceso
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al verificar acceso: ' . $e->getMessage()
            ], 500);
        }
    }
}