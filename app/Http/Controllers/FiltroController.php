<?php

namespace App\Http\Controllers;

use App\Models\Filtro;
use Illuminate\Http\Request;

class FiltroController extends Controller
{
    /**
     * Obtener un catálogo específico
     * 
     * @param string $catalogo
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtener($catalogo)
    {
        try {
            $metodo = $this->mapearCatalogo($catalogo);

            if (!method_exists(Filtro::class, $metodo)) {
                return response()->json([
                    'success' => false,
                    'message' => "Catálogo '{$catalogo}' no existe"
                ], 404);
            }

            $datos = Filtro::$metodo();

            return response()->json([
                'success' => true,
                'catalogo' => $catalogo,
                'data' => $datos,
                'total' => $datos->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener catálogo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener catálogo en formato para <select>
     * 
     * @param string $catalogo
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerParaSelect($catalogo)
    {
        try {
            $metodo = $this->mapearCatalogo($catalogo);

            if (!method_exists(Filtro::class, $metodo)) {
                return response()->json([
                    'success' => false,
                    'message' => "Catálogo '{$catalogo}' no existe"
                ], 404);
            }

            $datos = Filtro::$metodo();
            $select = Filtro::paraSelect($datos);

            return response()->json([
                'success' => true,
                'catalogo' => $catalogo,
                'data' => $select
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener catálogo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener catálogo en formato para Select2
     * 
     * @param string $catalogo
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerParaSelect2($catalogo)
    {
        try {
            $metodo = $this->mapearCatalogo($catalogo);

            if (!method_exists(Filtro::class, $metodo)) {
                return response()->json([
                    'success' => false,
                    'message' => "Catálogo '{$catalogo}' no existe"
                ], 404);
            }

            $datos = Filtro::$metodo();
            $select2 = Filtro::paraSelect2($datos);

            return response()->json([
                'success' => true,
                'results' => $select2 // Formato esperado por Select2
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener catálogo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener catálogo en formato para React Select
     * 
     * @param string $catalogo
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerParaReactSelect($catalogo)
    {
        try {
            $metodo = $this->mapearCatalogo($catalogo);

            if (!method_exists(Filtro::class, $metodo)) {
                return response()->json([
                    'success' => false,
                    'message' => "Catálogo '{$catalogo}' no existe"
                ], 404);
            }

            $datos = Filtro::$metodo();
            $reactSelect = Filtro::paraReactSelect($datos);

            return response()->json([
                'success' => true,
                'options' => $reactSelect // Formato esperado por React Select
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener catálogo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener múltiples catálogos de una vez
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerVarios(Request $request)
    {
        try {
            $catalogos = $request->input('catalogos', []);

            if (empty($catalogos)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debe proporcionar al menos un catálogo'
                ], 422);
            }

            $datos = Filtro::varios($catalogos);

            return response()->json([
                'success' => true,
                'data' => $datos,
                'total_catalogos' => count($datos)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener catálogos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener TODOS los catálogos disponibles
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerTodos()
    {
        try {
            $datos = Filtro::todos();

            return response()->json([
                'success' => true,
                'data' => $datos,
                'catalogos_disponibles' => array_keys($datos)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener catálogos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Buscar en un catálogo específico
     * 
     * @param string $catalogo
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function buscar($catalogo, Request $request)
    {
        try {
            $busqueda = $request->input('q', '');

            if (empty($busqueda)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debe proporcionar un término de búsqueda'
                ], 422);
            }

            $resultados = Filtro::buscar($catalogo, $busqueda);

            return response()->json([
                'success' => true,
                'catalogo' => $catalogo,
                'busqueda' => $busqueda,
                'data' => $resultados,
                'total' => $resultados->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al buscar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Buscar un elemento por ID
     * 
     * @param string $catalogo
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function buscarPorId($catalogo, $id)
    {
        try {
            $item = Filtro::buscarPorId($catalogo, $id);

            if (!$item) {
                return response()->json([
                    'success' => false,
                    'message' => 'Elemento no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $item
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al buscar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener catálogo con cache (optimizado)
     * 
     * @param string $catalogo
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerConCache($catalogo)
    {
        try {
            // Cache de 60 minutos por defecto
            $minutos = request()->input('cache', 60);
            $datos = Filtro::cache($catalogo, $minutos);

            return response()->json([
                'success' => true,
                'catalogo' => $catalogo,
                'data' => $datos,
                'total' => $datos->count(),
                'cached' => true,
                'cache_minutes' => $minutos
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener catálogo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Limpiar cache de catálogos
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function limpiarCache(Request $request)
    {
        try {
            $catalogo = $request->input('catalogo');
            Filtro::limpiarCache($catalogo);

            return response()->json([
                'success' => true,
                'message' => $catalogo 
                    ? "Cache de '{$catalogo}' limpiado" 
                    : 'Cache de todos los catálogos limpiado'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al limpiar cache: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Listar catálogos disponibles
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function listarCatalogos()
    {
        return response()->json([
            'success' => true,
            'catalogos' => [
                'usuarios',
                'modulos',
                'menus',
                'iconos',
                'materiales',
                'terminados',
                'departamentos',
                'tipos_pago',
                'perfiles',
                'mueblerias',
                'muebles',
                'clientes',
                'direcciones_clientes'
            ],
            'total' => 13
        ]);
    }

    /**
     * Mapear nombre de catálogo a método
     * 
     * @param string $catalogo
     * @return string
     */
    private function mapearCatalogo(string $catalogo): string
    {
        $mapeo = [
            'usuarios' => 'usuarios',
            'modulos' => 'modulos',
            'menus' => 'menus',
            'iconos' => 'iconos',
            'materiales' => 'materiales',
            'terminados' => 'terminados',
            'departamentos' => 'departamentos',
            'tipos_pago' => 'tiposPago',
            'tipospago' => 'tiposPago',
            'perfiles' => 'perfiles',
            'roles' => 'perfiles',
            'mueblerias' => 'mueblerias',
            'muebles' => 'muebles',
            'clientes' => 'clientes',
            'direcciones_clientes' => 'direccionesClientes',
            'direccionesclientes' => 'direccionesClientes'
        ];

        return $mapeo[strtolower($catalogo)] ?? $catalogo;
    }
}