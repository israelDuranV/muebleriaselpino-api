<?php

namespace App\Observers;

use App\Services\MenuServiceWithCache;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Role;

class RoleObserver
{
    protected $menuService;

    public function __construct(MenuServiceWithCache $menuService)
    {
        $this->menuService = $menuService;
    }

    /**
     * Handle the Role "created" event.
     */
    public function created(Role $role): void
    {
        $this->clearMenuCache();
    }

    /**
     * Handle the Role "updated" event.
     */
    public function updated(Role $role): void
    {
        $this->clearMenuCache();
    }

    /**
     * Handle the Role "deleted" event.
     */
    public function deleted(Role $role): void
    {
        $this->clearMenuCache();
    }

    /**
     * Limpia todo el caché de menús
     */
    protected function clearMenuCache(): void
    {
        Cache::tags(['menus'])->flush();
        $this->menuService->clearAllMenuCache();
    }
}