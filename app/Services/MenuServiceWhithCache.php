<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class MenuServiceWithCache
{
    /**
     * Obtiene el menú de navegación basado en los permisos del usuario
     * con soporte de caché
     * 
     * @param bool $useCache
     * @return array
     */
    public function getMenuForUser(bool $useCache = true)
    {
        $user = Auth::user();
        
        if (!$user) {
            return [];
        }

        $cacheKey = $this->getCacheKey($user->id);

        if ($useCache && config('menu.cache.enabled', true)) {
            return Cache::remember(
                $cacheKey,
                now()->addMinutes(config('menu.cache.ttl', 60)),
                fn() => $this->buildMenu($user)
            );
        }

        return $this->buildMenu($user);
    }

    /**
     * Construye el menú para el usuario
     * 
     * @param \App\Models\User $user
     * @return array
     */
    protected function buildMenu($user)
    {
        $menuStructure = $this->getMenuStructure();
        return $this->filterMenuByPermissions($menuStructure, $user);
    }

    /**
     * Define la estructura completa del menú
     * 
     * @return array
     */
    protected function getMenuStructure()
    {
        return [
            [
                'id' => 'dashboard',
                'label' => 'Dashboard',
                'icon' => 'dashboard',
                'route' => 'dashboard',
                'url' => '/dashboard',
                'permission' => null,
                'order' => 1,
                'badge' => null, // Para notificaciones
                'meta' => [
                    'description' => 'Panel principal de control',
                ],
            ],
            [
                'id' => 'users',
                'label' => 'Usuarios',
                'icon' => 'users',
                'route' => null,
                'url' => null,
                'permission' => 'users.view',
                'order' => 2,
                'children' => [
                    [
                        'id' => 'users-list',
                        'label' => 'Lista de Usuarios',
                        'icon' => 'list',
                        'route' => 'users.index',
                        'url' => '/users',
                        'permission' => 'users.view',
                        'order' => 1,
                    ],
                    [
                        'id' => 'users-create',
                        'label' => 'Crear Usuario',
                        'icon' => 'user-plus',
                        'route' => 'users.create',
                        'url' => '/users/create',
                        'permission' => 'users.create',
                        'order' => 2,
                    ],
                ],
            ],
            [
                'id' => 'roles',
                'label' => 'Roles y Permisos',
                'icon' => 'shield',
                'route' => null,
                'url' => null,
                'permission' => 'roles.view',
                'order' => 3,
                'children' => [
                    [
                        'id' => 'roles-list',
                        'label' => 'Lista de Roles',
                        'icon' => 'list',
                        'route' => 'roles.index',
                        'url' => '/roles',
                        'permission' => 'roles.view',
                        'order' => 1,
                    ],
                    [
                        'id' => 'permissions-list',
                        'label' => 'Permisos',
                        'icon' => 'key',
                        'route' => 'permissions.index',
                        'url' => '/permissions',
                        'permission' => 'permissions.view',
                        'order' => 2,
                    ],
                ],
            ],
            [
                'id' => 'products',
                'label' => 'Productos',
                'icon' => 'package',
                'route' => null,
                'url' => null,
                'permission' => 'products.view',
                'order' => 4,
                'children' => [
                    [
                        'id' => 'products-list',
                        'label' => 'Lista de Productos',
                        'icon' => 'list',
                        'route' => 'products.index',
                        'url' => '/products',
                        'permission' => 'products.view',
                        'order' => 1,
                    ],
                    [
                        'id' => 'products-create',
                        'label' => 'Crear Producto',
                        'icon' => 'plus',
                        'route' => 'products.create',
                        'url' => '/products/create',
                        'permission' => 'products.create',
                        'order' => 2,
                    ],
                    [
                        'id' => 'categories',
                        'label' => 'Categorías',
                        'icon' => 'folder',
                        'route' => 'categories.index',
                        'url' => '/categories',
                        'permission' => 'categories.view',
                        'order' => 3,
                    ],
                ],
            ],
            [
                'id' => 'reports',
                'label' => 'Reportes',
                'icon' => 'chart-bar',
                'route' => null,
                'url' => null,
                'permission' => 'reports.view',
                'order' => 5,
                'children' => [
                    [
                        'id' => 'sales-report',
                        'label' => 'Reporte de Ventas',
                        'icon' => 'trending-up',
                        'route' => 'reports.sales',
                        'url' => '/reports/sales',
                        'permission' => 'reports.sales',
                        'order' => 1,
                    ],
                    [
                        'id' => 'inventory-report',
                        'label' => 'Reporte de Inventario',
                        'icon' => 'clipboard',
                        'route' => 'reports.inventory',
                        'url' => '/reports/inventory',
                        'permission' => 'reports.inventory',
                        'order' => 2,
                    ],
                ],
            ],
            [
                'id' => 'settings',
                'label' => 'Configuración',
                'icon' => 'settings',
                'route' => 'settings',
                'url' => '/settings',
                'permission' => 'settings.view',
                'order' => 6,
            ],
        ];
    }

    /**
     * Filtra el menú según los permisos del usuario
     * 
     * @param array $menu
     * @param \App\Models\User $user
     * @return array
     */
    protected function filterMenuByPermissions(array $menu, $user)
    {
        $filteredMenu = [];

        foreach ($menu as $item) {
            if ($item['permission'] !== null && !$user->can($item['permission'])) {
                continue;
            }

            $menuItem = $item;

            if (isset($item['children']) && is_array($item['children'])) {
                $filteredChildren = $this->filterMenuByPermissions($item['children'], $user);
                
                if (empty($filteredChildren)) {
                    continue;
                }
                
                $menuItem['children'] = $filteredChildren;
            }

            $filteredMenu[] = $menuItem;
        }

        return $filteredMenu;
    }

    /**
     * Obtiene el menú con información adicional del usuario
     * 
     * @return array
     */
    public function getFullMenuData()
    {
        $user = Auth::user();
        
        return [
            'user' => [
                'id' => $user?->id,
                'name' => $user?->name,
                'email' => $user?->email,
                'roles' => $user?->roles->pluck('name'),
                'permissions' => $user?->getAllPermissions()->pluck('name'),
            ],
            'menu' => $this->getMenuForUser(),
        ];
    }

    /**
     * Genera la clave de caché para el menú del usuario
     * 
     * @param int $userId
     * @return string
     */
    protected function getCacheKey(int $userId): string
    {
        $prefix = config('menu.cache.prefix', 'user_menu_');
        return $prefix . $userId;
    }

    /**
     * Limpia el caché del menú para un usuario específico
     * 
     * @param int $userId
     * @return void
     */
    public function clearUserMenuCache(int $userId): void
    {
        Cache::forget($this->getCacheKey($userId));
    }

    /**
     * Limpia todo el caché de menús
     * 
     * @return void
     */
    public function clearAllMenuCache(): void
    {
        // Esta función requeriría conocer todos los IDs de usuarios
        // o usar un patrón de caché más sofisticado
        Cache::flush();
    }
}