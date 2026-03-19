<?php

namespace App\Http\Controllers;

use App\Models\Terminado;
use App\Models\Banco;
use App\Models\Departamento;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CatalogoController extends Controller
{
    /**
     * Mapeo de catálogos con sus modelos y configuraciones
     */
    private $catalogos = [
        'terminados' => [
            'model' => Terminado::class,
            'table' => 'terminado',
            'primary_key' => 'terminado_id',
            'name_field' => 'terminado',
            'singular' => 'Terminado',
            'plural' => 'Terminados',
            'fillable' => ['terminado', 'descripcion'],
            'validation' => [
                'terminado' => 'required|string|max:100',
                'descripcion' => 'nullable|string|max:500',
            ],
        ],
        'bancos' => [
            'model' => Banco::class,
            'table' => 'bancos',
            'primary_key' => 'banco_id',
            'name_field' => 'banco',
            'singular' => 'Banco',
            'plural' => 'Bancos',
            'fillable' => ['banco', 'descripcion'],
            'validation' => [
                'banco' => 'required|string|max:100',
                'descripcion' => 'nullable|string|max:500',
            ],
        ],
        'departamentos' => [
            'model' => Departamento::class,
            'table' => 'departamento',
            'primary_key' => 'departamento_id',
            'name_field' => 'departamento',
            'singular' => 'Departamento',
            'plural' => 'Departamentos',
            'fillable' => ['name', 'descripcion'],
            'validation' => [
                'bane' => 'required|string|max:100',
                'descripcion' => 'nullable|string|max:500',
            ],
        ],
        'materiales' => [
            'model' => Material::class,
            'table' => 'materiales',
            'primary_key' => 'materiales_id',
            'name_field' => 'material',
            'singular' => 'Material',
            'plural' => 'Materiales',
            'fillable' => ['material', 'descripcion'],
            'validation' => [
                'material' => 'required|string|max:100',
                'descripcion' => 'nullable|string|max:500',
            ],
        ],
    ];

    /**
     * Obtener la configuración de un catálogo
     *
     * @param string $catalogo
     * @return array|null
     */
    private function getCatalogoConfig(string $catalogo): ?array
    {
        return $this->catalogos[$catalogo] ?? null;
    }

    /**
     * Listar todos los elementos de un catálogo
     *
     * @param string $catalogo
     * @return JsonResponse
     */
    public function index(string $catalogo): JsonResponse
    {
        try {
            $config = $this->getCatalogoConfig($catalogo);

            if (!$config) {
                return response()->json([
                    'success' => false,
                    'message' => 'Catálogo no válido',
                    'catalogos_disponibles' => array_keys($this->catalogos),
                ], 400);
            }

            $model = $config['model'];
            $items = $model::orderBy($config['name_field'], 'asc')->get();

            return response()->json([
                'success' => true,
                'catalogo' => $config['plural'],
                'data' => $items,
                'total' => $items->count(),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Error al obtener {$config['plural']}",
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obtener un elemento específico de un catálogo
     *
     * @param string $catalogo
     * @param int $id
     * @return JsonResponse
     */
    public function show(string $catalogo, int $id): JsonResponse
    {
        try {
            $config = $this->getCatalogoConfig($catalogo);

            if (!$config) {
                return response()->json([
                    'success' => false,
                    'message' => 'Catálogo no válido',
                ], 400);
            }

            $model = $config['model'];
            $item = $model::find($id);

            if (!$item) {
                return response()->json([
                    'success' => false,
                    'message' => "{$config['singular']} no encontrado",
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $item,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Error al obtener {$config['singular']}",
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Crear un nuevo elemento en un catálogo
     *
     * @param Request $request
     * @param string $catalogo
     * @return JsonResponse
     */
    public function store(Request $request, string $catalogo): JsonResponse
    {
        try {
            $config = $this->getCatalogoConfig($catalogo);

            if (!$config) {
                return response()->json([
                    'success' => false,
                    'message' => 'Catálogo no válido',
                ], 400);
            }

            // Validación
            $validator = Validator::make($request->all(), $config['validation']);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $validator->errors(),
                ], 422);
            }

            DB::beginTransaction();

            $model = $config['model'];
            $data = $request->only($config['fillable']);
            $item = $model::create($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "{$config['singular']} creado exitosamente",
                'data' => $item,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => "Error al crear {$config['singular']}",
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Actualizar un elemento de un catálogo
     *
     * @param Request $request
     * @param string $catalogo
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, string $catalogo, int $id): JsonResponse
    {
        try {
            $config = $this->getCatalogoConfig($catalogo);

            if (!$config) {
                return response()->json([
                    'success' => false,
                    'message' => 'Catálogo no válido',
                ], 400);
            }

            // Validación (haciendo campos opcionales para actualización)
            $validation = array_map(function($rule) {
                return str_replace('required', 'sometimes|required', $rule);
            }, $config['validation']);

            $validator = Validator::make($request->all(), $validation);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $validator->errors(),
                ], 422);
            }

            DB::beginTransaction();

            $model = $config['model'];
            $item = $model::find($id);

            if (!$item) {
                return response()->json([
                    'success' => false,
                    'message' => "{$config['singular']} no encontrado",
                ], 404);
            }

            $data = $request->only($config['fillable']);
            $item->update($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "{$config['singular']} actualizado exitosamente",
                'data' => $item,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => "Error al actualizar {$config['singular']}",
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Eliminar un elemento de un catálogo
     *
     * @param string $catalogo
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(string $catalogo, int $id): JsonResponse
    {
        try {
            $config = $this->getCatalogoConfig($catalogo);

            if (!$config) {
                return response()->json([
                    'success' => false,
                    'message' => 'Catálogo no válido',
                ], 400);
            }

            DB::beginTransaction();

            $model = $config['model'];
            $item = $model::find($id);

            if (!$item) {
                return response()->json([
                    'success' => false,
                    'message' => "{$config['singular']} no encontrado",
                ], 404);
            }

            $item->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "{$config['singular']} eliminado exitosamente",
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            // Error de integridad referencial
            if ($e->getCode() == 23000 || strpos($e->getMessage(), 'foreign key constraint') !== false) {
                return response()->json([
                    'success' => false,
                    'message' => "No se puede eliminar el {$config['singular']} porque está siendo usado en otros registros",
                ], 409);
            }

            return response()->json([
                'success' => false,
                'message' => "Error al eliminar {$config['singular']}",
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Buscar elementos en un catálogo
     *
     * @param Request $request
     * @param string $catalogo
     * @return JsonResponse
     */
    public function search(Request $request, string $catalogo): JsonResponse
    {
        try {
            $config = $this->getCatalogoConfig($catalogo);

            if (!$config) {
                return response()->json([
                    'success' => false,
                    'message' => 'Catálogo no válido',
                ], 400);
            }

            $search = $request->input('q', '');
            $model = $config['model'];

            $query = $model::query();

            if ($search) {
                $query->where($config['name_field'], 'like', "%{$search}%");
                
                // Buscar también en descripción si existe
                if (in_array('descripcion', $config['fillable'])) {
                    $query->orWhere('descripcion', 'like', "%{$search}%");
                }
            }

            $items = $query->orderBy($config['name_field'], 'asc')->get();

            return response()->json([
                'success' => true,
                'catalogo' => $config['plural'],
                'data' => $items,
                'total' => $items->count(),
                'search' => $search,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Error al buscar en {$config['plural']}",
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Listar todos los catálogos disponibles
     *
     * @return JsonResponse
     */
    public function catalogos(): JsonResponse
    {
        try {
            $result = [];

            foreach ($this->catalogos as $key => $config) {
                $result[] = [
                    'key' => $key,
                    'nombre' => $config['plural'],
                    'singular' => $config['singular'],
                    'endpoint' => "/api/catalogos/{$key}",
                ];
            }

            return response()->json([
                'success' => true,
                'message' => 'Catálogos disponibles',
                'data' => $result,
                'total' => count($result),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener catálogos',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obtener todos los catálogos con sus datos
     *
     * @return JsonResponse
     */
    public function all(): JsonResponse
    {
        try {
            $result = [];

            foreach ($this->catalogos as $key => $config) {
                $model = $config['model'];
                $items = $model::orderBy($config['name_field'], 'asc')->get();

                $result[$key] = [
                    'nombre' => $config['plural'],
                    'total' => $items->count(),
                    'data' => $items,
                ];
            }

            return response()->json([
                'success' => true,
                'message' => 'Todos los catálogos obtenidos',
                'data' => $result,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener todos los catálogos',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obtener estadísticas de un catálogo
     *
     * @param string $catalogo
     * @return JsonResponse
     */
    public function stats(string $catalogo): JsonResponse
    {
        try {
            $config = $this->getCatalogoConfig($catalogo);

            if (!$config) {
                return response()->json([
                    'success' => false,
                    'message' => 'Catálogo no válido',
                ], 400);
            }

            $model = $config['model'];
            $total = $model::count();

            $stats = [
                'catalogo' => $config['plural'],
                'total_registros' => $total,
                'con_descripcion' => $model::whereNotNull('descripcion')
                    ->where('descripcion', '!=', '')
                    ->count(),
                'sin_descripcion' => $model::whereNull('descripcion')
                    ->orWhere('descripcion', '')
                    ->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Error al obtener estadísticas de {$config['plural']}",
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}