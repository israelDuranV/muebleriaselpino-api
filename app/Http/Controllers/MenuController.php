<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MenuService;
use Illuminate\Http\JsonResponse;

class MenuController extends Controller
{
    protected $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }

    /**
     * Retorna el menú de navegación del usuario autenticado
     * 
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $menu = $this->menuService->getMenuForUser();

            return response()->json([
                'success' => true,
                'data' => $menu,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el menú',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Retorna el menú con información del usuario
     * 
     * @return JsonResponse
     */
    public function full(): JsonResponse
    {
        try {
            $data = $this->menuService->getFullMenuData();

            return response()->json([
                'success' => true,
                'data' => $data,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los datos completos del menú',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}