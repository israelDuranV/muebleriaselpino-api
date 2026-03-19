<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\InventarioGeneral;
use App\Models\Pedido;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PedidoController extends Controller
{
    /**
     * GET - Listar todos los pedidos activos
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $pedidos = Pedido::getPedidos();

            return response()->json([
                'success' => true,
                'data' => $pedidos,
                'total' => count($pedidos),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener pedidos',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * GET - Obtener siguiente código de pedido
     *
     * @return JsonResponse
     */
    public function getNextCodigo(): JsonResponse
    {
        try {
            $codigo = Pedido::getNextCodigoPedido();

            return response()->json([
                'success' => true,
                'data' => [
                    'codigo' => $codigo,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener código',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * SAVE - Crear un nuevo pedido
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'muebles_id' => 'required|integer|exists:muebles,muebles_id',
            'muebleria_id' => 'required|integer|exists:mueblerias,mueblerias_id',
            'cantidad' => 'required|integer|min:1',
            'fecha_entrega' => 'nullable|date',
            'descripcion' => 'nullable|string|max:300',
        ]);
        $data = $validator->validated();
        $data['usuario_id'] = auth()->id();

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Errores de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();

        try {
            $result = Pedido::savePedido($data);

            if (!$result) {
                throw new \Exception('Error al guardar el pedido');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pedido creado exitosamente',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error al crear pedido',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * EDIT - Actualizar un pedido existente
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'muebles_id' => 'required|integer|exists:muebles,muebles_id',
            'muebleria_id' => 'required|integer|exists:mueblerias,mueblerias_id',
            'cantidad' => 'required|integer|min:1',
            'fecha' => 'nullable|date',
            'descripcion' => 'nullable|string|max:300',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Errores de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();

        try {

            $pedido = Pedido::find($id);
            if (!$pedido) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pedido no encontrado',
                ], 404);
            }

            $pedido->update([
                'muebles_id'   => $request->input('muebles_id'),
                'usuario_id'   => auth()->id(),
                'muebleria_id' => $request->input('muebleria_id'),
                'cantidad'     => $request->input('cantidad'),
                'fecha_entrega'=> $request->input('fecha'),
                'descripcion'  => $request->input('descripcion'),
            ]);
        
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pedido actualizado exitosamente',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar pedido',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function sendToProduccion(Request $request,  int $id): JsonResponse
    {
      
        $validator = Validator::make($request->all(), [
            'muebles_id' => 'required|integer|exists:muebles,muebles_id',
            'muebleria_id' => 'required|integer|exists:mueblerias,mueblerias_id',
            'cantidad' => 'required|integer',
            'codigo' => 'required|integer',
            'fecha' => 'nullable|date',
            'descripcion' => 'nullable|string|max:300',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Errores de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();

        try {
            $pedido = Pedido::find($id);
            if (!$pedido) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pedido no encontrado',
                ], 404);
            }

            InventarioGeneral::create([
                'muebles_id'     => $request->input('muebles_id'),
                'pedido_id'      => $id,
                'codigo_pedido'  => $request->input('codigo'),
                'fecha_produccion'=> now(), 
                'cantidad'=> $request->input('cantidad'), 
                'estatus'        => 1,
                'usuario_id'     => auth()->id(),
                'muebleria_id'   => $request->input('muebleria_id'),
                'fecha_entrega'  => $request->input('fecha'),
                'descripcion'    => $request->input('descripcion'),
            ]);
            $pedido->update([
                'cantidad'=> $request->input('cantidad'),
                'produccion'=> 1
            ]);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pedido enviado a producción exitosamente',
            ], 200);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error al enviar a producción'.$e->getMessage(),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * DELETE - Disminuir cantidad de pedido
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        DB::beginTransaction();

        try {
            $pedido = Pedido::find($id);
            if (!$pedido) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pedido no encontrado',
                ], 404);
            }
    
            $pedido->delete();
    
            DB::commit();
    
            return response()->json([
                'success' => true,
                'message' => 'Pedido eliminado exitosamente'
            ], 200);
    
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar pedido',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function decrementCantidad(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'pedido_id' => 'required|integer|exists:pedidos,pedidos_id',
            'mueble_id' => 'required|integer|exists:muebles,muebles_id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Errores de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();

        try {
            $result = Pedido::decrementPedido(
                $request->pedido_id,
                $request->mueble_id
            );

            if (!$result) {
                throw new \Exception('Error al decrementar cantidad');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cantidad decrementada exitosamente',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error al decrementar cantidad',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obtener pedido por ID (usando Eloquent)
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $pedido = Pedido::with(['mueble', 'usuario', 'muebleria'])
                ->find($id);

            if (!$pedido) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pedido no encontrado',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $pedido,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener pedido',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obtener pedidos por mueblería
     *
     * @param int $muebleriaId
     * @return JsonResponse
     */
    public function byMuebleria(int $muebleriaId): JsonResponse
    {
        try {
            $pedidos = Pedido::with(['mueble', 'usuario'])
                ->activos()
                ->byMuebleria($muebleriaId)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $pedidos,
                'total' => $pedidos->count(),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener pedidos',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obtener pedidos por usuario
     *
     * @param int $usuarioId
     * @return JsonResponse
     */
    public function byUsuario(int $usuarioId): JsonResponse
    {
        try {
            $pedidos = Pedido::with(['mueble', 'muebleria'])
                ->activos()
                ->byUsuario($usuarioId)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $pedidos,
                'total' => $pedidos->count(),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener pedidos',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Estadísticas de pedidos
     *
     * @return JsonResponse
     */
    public function estadisticas(): JsonResponse
    {
        try {
            $stats = [
                'total_activos' => Pedido::activos()->count(),
                'total_produccion' => Pedido::enProduccion()->count(),
                'total_cantidad_activos' => Pedido::activos()->sum('cantidad'),
                'por_muebleria' => Pedido::activos()
                    ->select('mueblerias_id', DB::raw('COUNT(*) as total, SUM(cantidad) as cantidad_total'))
                    ->groupBy('mueblerias_id')
                    ->get(),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener estadísticas',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
