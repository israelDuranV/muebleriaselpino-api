<?php

namespace App\Observers;

use App\Services\MenuServiceWithCache;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionObserver
{
    protected $menuService;

    public function __construct(MenuServiceWithCache $menuService)
    {
        $this->menuService = $menuService;
    }

    /**
     * Handle the Permission "created" event.
     */
    public function created(Permission $permission): void
    {
        $this->clearMenuCache();
    }

    /**
     * Handle the Permission "updated" event.
     */
    public function updated(Permission $permission): void
    {
        $this->clearMenuCache();
    }

    /**
     * Handle the Permission "deleted" event.
     */
    public function deleted(Permission $permission): void
    {
        $this->clearMenuCache();
    }

    /**
     * Limpia todo el caché de menús
     */
    protected function clearMenuCache(): void
    {
        // Limpiar todo el caché relacionado con menús
        Cache::tags(['menus'])->flush();
        
        // O si no usas tags, limpiar por patrón
        $this->menuService->clearAllMenuCache();
    }
}