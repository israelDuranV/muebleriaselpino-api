<?php

namespace App\Http\Controllers;

use App\Models\InventarioGeneral;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProduccionController extends Controller
{
    /**
     * GET - Obtener toda la producción activa (estatus = 1)
     */
    public function index(): JsonResponse
    {
        try {
            $produccion = InventarioGeneral::spConsultaProduccion('GET');

            return response()->json([
                'success' => true,
                'message' => 'Ok',
                'data'    => $produccion,
                'total'   => count($produccion),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la producción.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
    public function start(Request $request,int $id): JsonResponse
    {
        try {
            InventarioGeneral::spConsultaProduccion('START', [
                'id' => $request->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Producción iniciada correctamente.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al iniciar la producción.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * END - Marcar fecha de término de producción
     */
    public function end(Request $request, int $id): JsonResponse
    {
        try {
            InventarioGeneral::spConsultaProduccion('END', [
                'id' => $request->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Producción finalizada correctamente.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al finalizar la producción.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * SEND - Enviar inventario a mueblería (cambia estatus, registra movimiento)
     */
    public function send(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'id'          => 'required|integer|exists:inventario_general,inventarios_id',
            'muebleria'   => 'required|integer|exists:mueblerias,mueblerias_id',
            'procedencia' => 'required|integer',
        ], [
            'id.required'          => 'El ID del inventario es obligatorio.',
            'id.exists'            => 'El inventario especificado no existe.',
            'muebleria.required'   => 'La mueblería es obligatoria.',
            'muebleria.exists'     => 'La mueblería especificada no existe.',
            'procedencia.required' => 'La procedencia es obligatoria.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            DB::transaction(function () use ($request) {
                InventarioGeneral::spConsultaProduccion('SEND', [
                    'id'          => $request->id,
                    'muebleria'   => $request->muebleria,
                    'procedencia' => $request->procedencia,
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Inventario enviado a mueblería correctamente.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al enviar el inventario.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Método genérico para llamar cualquier acción del SP desde una sola ruta
     * Útil si prefieres manejar todo desde un único endpoint POST
     */
    public function handle(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'accion' => 'required|string|in:GET,START,END,SEND',
        ], [
            'accion.required' => 'La acción es obligatoria.',
            'accion.in'       => 'Acción no válida. Use: GET, START, END o SEND.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $accion = strtoupper($request->accion);

        return match ($accion) {
            'GET'   => $this->index(),
            'START' => $this->start($request),
            'END'   => $this->end($request),
            'SEND'  => $this->send($request),
        };
    }
}
