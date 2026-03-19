<?php


namespace App\Http\Controllers;


use App\Http\Requests\MuebleRequest;
use App\Models\Mueble;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MueblesController extends Controller
{
    /**
     * GETMUEBLES - Listar todos los muebles
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $muebles = Mueble::getAllMuebles();
            $muebles = array_map(function ($mueble) {
                $mueble->fotos_array = !empty($mueble->fotografia)
                    ? array_filter(array_map('trim', explode(',', $mueble->fotografia)))
                    : [];
                return $mueble;
            }, $muebles);

            return response()->json([
                'success' => true,
                'data' => $muebles,
                'total' => count($muebles),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener muebles',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function getMuebles(Request $request): JsonResponse
    {
        try {
            $perPage = $request->input('per_page', 10);
            $page = $request->input('page', 1);
            $muebles = Mueble::paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'success' => true,
                'data' => $muebles->items(),   // los registros de la página actual
                'total' => $muebles->total(),  // total de registros en la tabla
                'per_page' => $muebles->perPage(),
                'current_page' => $muebles->currentPage(),
                'last_page' => $muebles->lastPage(),
                'from' => $muebles->firstItem(), // equivale a from
                'to'  => $muebles->lastItem(),  // equivale a to
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener muebles',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    /**
     * GETMUEBLE - Obtener un mueble específico
     *
     * @param int $id
     * @return JsonResponse
     */

    public function show(string|int  $id): JsonResponse
    {
        $id = (int) $id;
        try {
            $mueble = Mueble::getMuebleById($id);

            if (!$mueble) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mueble no encontrado',
                ], 404);
            }

            // Procesar fotos
            $mueble->fotos_array = !empty($mueble->fotografia)
                ? array_filter(array_map('trim', explode(',', $mueble->fotografia)))
                : [];

            return response()->json([
                'success' => true,
                'data' => $mueble,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener mueble',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * GETDEPARTAMENTO - Obtener muebles por departamento
     *
     * @param int $departamentoId
     * @return JsonResponse
     */
    public function byDepartamento(int $departamentoId): JsonResponse
    {
        try {
            $muebles = Mueble::getMueblesByDepartamento($departamentoId);

            // Procesar fotos
            $muebles = array_map(function ($mueble) {
                $mueble->fotos_array = !empty($mueble->fotografia)
                    ? array_filter(array_map('trim', explode(',', $mueble->fotografia)))
                    : [];
                return $mueble;
            }, $muebles);

            return response()->json([
                'success' => true,
                'data' => $muebles,
                'total' => count($muebles),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener muebles del departamento',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * INSERTMUEBLES - Crear un nuevo mueble
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(MuebleRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $mueble = Mueble::create([
                'nombre' => $request->mueble,
                'terminado_id' => $request->terminado_id,
                'material_id' => $request->material_id,
                'departamento_id' => $request->departamento_id,
                'sincera' => $request->sincera,
                'encerado' => $request->encerado,
                'costo' => $request->costo,
                'barniz' => $request->barniz
            ]);

            $mueble->fotos()->create([
                'muebles_id' => $mueble->muebles_id,
                'orden' => 1,
                'descripcion' => $request->mueble,
                'url' => $request->imageUrl,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Mueble creado exitosamente',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            // Eliminar fotos subidas si hubo error
            if (isset($request->imageUrl)) {
                Storage::disk('public')->delete($request->imageUrl);
            }

            return response()->json([
                'success' => false,
                'message' => 'Error al crear mueble',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(MuebleRequest $request, $id): JsonResponse
    {
        DB::beginTransaction();

        try {
            $mueble = Mueble::where('muebles_id', $id)->firstOrFail();
            $mueble->update([
                'nombre' => $request->mueble,
                'terminado_id' => $request->terminado_id,
                'material_id' => $request->material_id,
                'departamento_id' => $request->departamento_id,
                'sincera' => $request->sincera,
                'encerado' => $request->encerado,
                'costo' => $request->costo,
                'barniz' => $request->barniz
            ]);
            if ($request->filled('imageUrl')) {
                $mueble->fotos()->create([
                    'orden' => 1,
                    'descripcion' => $request->mueble,
                    'url' => $request->imageUrl,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Mueble editado exitosamente',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->filled('imageUrl')) {
                Storage::disk('public')->delete($request->imageUrl);
            }

            return response()->json([
                'success' => false,
                'message' => 'Error al editar mueble',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * DELETE - Eliminar un mueble
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        DB::beginTransaction();

        try {
            // Verificar que el mueble existe
            $mueble = Mueble::find($id);
            if (!$mueble) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mueble no encontrado',
                ], 404);
            }

            // Eliminar fotos del storage si existen
            if ($mueble->fotografia) {
                $fotos = array_filter(array_map('trim', explode(',', $mueble->fotografia)));
                foreach ($fotos as $foto) {
                    Storage::disk('public')->delete($foto);
                }
            }

            $result = Mueble::deleteMueble($id);

            if (!$result) {
                throw new \Exception('Error al eliminar el mueble');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Mueble eliminado exitosamente',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar mueble',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Agregar foto a un mueble existente
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function addFoto(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'foto' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
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
            // Verificar que el mueble existe
            $mueble = Mueble::find($id);
            if (!$mueble) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mueble no encontrado',
                ], 404);
            }

            // Subir foto
            $path = $this->uploadFoto($request->file('foto'));

            // Actualizar mueble con nueva foto
            $result = Mueble::updateMueble($id, [
                'fotografia' => $path
            ]);

            if (!$result) {
                throw new \Exception('Error al agregar foto');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Foto agregada exitosamente',
                'data' => [
                    'foto_url' => Storage::disk('public')->url($path),
                ],
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            // Eliminar foto subida si hubo error
            if (isset($path)) {
                Storage::disk('public')->delete($path);
            }

            return response()->json([
                'success' => false,
                'message' => 'Error al agregar foto',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * DELETEFOTO - Eliminar una foto específica del mueble
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function deleteFoto(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'foto_url' => 'required|string',
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
            $result = Mueble::deleteFoto($id, $request->foto_url);

            if (!$result) {
                throw new \Exception('Error al eliminar foto');
            }

            // Eliminar del storage
            Storage::disk('public')->delete($request->foto_url);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Foto eliminada exitosamente',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar foto',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obtener muebles con filtros avanzados (usando Eloquent)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function filtrar(Request $request): JsonResponse
    {
        try {
            $query = Mueble::with(['terminado', 'material', 'departamento',  'fotos']);

            // Filtros
            if ($request->has('departamento_id')) {
                $query->byDepartamento($request->departamento_id);
            }

            if ($request->has('material_id')) {
                $query->byMaterial($request->material_id);
            }

            if ($request->has('terminado_id')) {
                $query->byTerminado($request->terminado_id);
            }

            if ($request->has('search')) {
                $query->search($request->search);
            }

            if ($request->has('con_stock') && $request->boolean('con_stock')) {
                $query->conStock();
            }

            // Ordenamiento
            if ($request->has('order_by_costo')) {
                $query->orderByCosto($request->order_by_costo);
            }

            // Paginación
            $perPage = $request->input('per_page', 15);
            $muebles = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $muebles->items(),
                'meta' => [
                    'total' => $muebles->total(),
                    'per_page' => $muebles->perPage(),
                    'current_page' => $muebles->currentPage(),
                    'last_page' => $muebles->lastPage(),
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al filtrar muebles',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Estadísticas de muebles
     *
     * @return JsonResponse
     */
    public function estadisticas(): JsonResponse
    {
        try {
            $stats = [
                'total_muebles' => Mueble::count(),
                'total_con_stock' => Mueble::conStock()->count(),
                'stock_total' => Mueble::sum('stock'),
                'costo_promedio' => Mueble::avg('costo'),
                'por_departamento' => Mueble::select('departamento_id', DB::raw('COUNT(*) as total'))
                    ->groupBy('departamento_id')
                    ->with('departamento:departamento_id,departamento')
                    ->get(),
                'por_material' => Mueble::select('materiales_id', DB::raw('COUNT(*) as total'))
                    ->groupBy('materiales_id')
                    ->with('material:materiales_id,material')
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

    /**
     * Subir foto al storage
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return string
     */
    private function uploadFoto($file): string
    {
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        return $file->storeAs('muebles', $filename, 'public');
    }
}
