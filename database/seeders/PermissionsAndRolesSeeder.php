<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsAndRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Resetear cache de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear permisos
        $permissions = [
            // Usuarios
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            
            // Roles y Permisos
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',
            'permissions.view',
            'permissions.create',
            'permissions.edit',
            'permissions.delete',
            
            // Productos
            'products.view',
            'products.create',
            'products.edit',
            'products.delete',
            
            // Categorías
            'categories.view',
            'categories.create',
            'categories.edit',
            'categories.delete',
            
            // Reportes
            'reports.view',
            'reports.sales',
            'reports.inventory',
            
            // Configuración
            'settings.view',
            'settings.edit',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Crear roles y asignar permisos

        // Super Admin - Todos los permisos
        $superAdmin = Role::create(['name' => 'Super Admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // Administrador - Permisos administrativos
        $admin = Role::create(['name' => 'Administrador']);
        $admin->givePermissionTo([
            'users.view',
            'users.create',
            'users.edit',
            'products.view',
            'products.create',
            'products.edit',
            'products.delete',
            'categories.view',
            'categories.create',
            'categories.edit',
            'categories.delete',
            'reports.view',
            'reports.sales',
            'reports.inventory',
            'settings.view',
        ]);

        // Vendedor - Permisos limitados
        $vendedor = Role::create(['name' => 'Vendedor']);
        $vendedor->givePermissionTo([
            'products.view',
            'categories.view',
            'reports.view',
            'reports.sales',
        ]);

        // Usuario básico - Permisos mínimos
        $usuario = Role::create(['name' => 'Usuario']);
        $usuario->givePermissionTo([
            'products.view',
            'categories.view',
        ]);

        $this->command->info('Permisos y roles creados exitosamente!');
    }
}