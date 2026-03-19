<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use \stdClass;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Usuario registrado exitosamente',
            'data' => $user,
            'token_type' => 'Bearer',
            'access_token' => $token
        ], 201);
    }
    public function login(Request $request)
    {
        $user = User::where('email',$request->email)->first();
        $mueblerias_asignadas = $user->mueblerias()->get();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }       
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Hola! ' . $user->name,
            'user' => $user,
            'mueblerias_asignadas'=>$mueblerias_asignadas,
            'success'=>true,
            'token_type' => 'Bearer',
            'access_token' => $token
        ]);
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Sesión cerrada correctamente']);
    }
}
