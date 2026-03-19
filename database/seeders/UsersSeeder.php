<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar que existan los roles necesarios
        $this->ensureRolesExist();

        // Obtener roles
        $superAdmin = Role::where('name', 'Super Admin')->orWhere('id', 1)->first();
        $administrador = Role::where('name', 'Administrador')->orWhere('id', 2)->first();
        $vendedor = Role::where('name', 'Vendedor')->orWhere('id', 3)->first();
        $usuario = Role::where('name', 'Usuario')->orWhere('id', 4)->first();

        // 1. Usuario Israel Duran - Administrador
        $israelDuran = User::updateOrCreate(
            ['email' => 'israel_duran@outlook.com'],
            [
                'name' => 'Israel Duran',
                'password' => bcrypt('sanguisetcinis'),
                'phone' => '555-1001',
                'address' => 'Av. Principal 100, Ciudad de México',
                'is_active' => 1,
            ]
        );
        
        
        $israelDuran->syncRoles([$administrador->id]);
        
        // Asignar mueblerías a Israel
        $israelDuran->mueblerias()->attach([1, 2, 3], ['is_primary' => false]);
        $israelDuran->mueblerias()->updateExistingPivot(1, ['is_primary' => true]);

        // 2. Super Admin
        $superAdminUser = User::create([
            'name' => 'Super Administrador',
            'email' => 'superadmin@mueblerias.com',
            'password' => Hash::make('password123'),
            'phone' => '555-1000',
            'address' => 'Oficinas Centrales, CDMX',
            'is_active' => true,
        ]);
        $superAdminUser->syncRoles([$superAdmin->id]);
        $superAdminUser->mueblerias()->attach([1, 2, 3, 4, 5], ['is_primary' => false]);
        $superAdminUser->mueblerias()->updateExistingPivot(1, ['is_primary' => true]);

        // 3. Administrador Regional
        $adminRegional = User::create([
            'name' => 'María González',
            'email' => 'maria.gonzalez@mueblerias.com',
            'password' => Hash::make('password123'),
            'phone' => '555-1002',
            'address' => 'Zona Norte, Monterrey',
            'is_active' => true,
        ]);
        $adminRegional->syncRoles([$administrador->id]);
        $adminRegional->mueblerias()->attach([4, 5, 6], ['is_primary' => false]);
        $adminRegional->mueblerias()->updateExistingPivot(4, ['is_primary' => true]);

        // 4. Vendedor Principal
        $vendedorPrincipal = User::create([
            'name' => 'Carlos Ramírez',
            'email' => 'carlos.ramirez@mueblerias.com',
            'password' => Hash::make('password123'),
            'phone' => '555-2001',
            'address' => 'Col. Centro, Guadalajara',
            'is_active' => true,
        ]);
        $vendedorPrincipal->syncRoles([$vendedor->id]);
        $vendedorPrincipal->mueblerias()->attach([1, 2], ['is_primary' => false]);
        $vendedorPrincipal->mueblerias()->updateExistingPivot(1, ['is_primary' => true]);

        // 5. Vendedor Zona Sur
        $vendedorSur = User::create([
            'name' => 'Ana Martínez',
            'email' => 'ana.martinez@mueblerias.com',
            'password' => Hash::make('password123'),
            'phone' => '555-2002',
            'address' => 'Zona Sur, Puebla',
            'is_active' => true,
        ]);
        $vendedorSur->syncRoles([$vendedor->id]);
        $vendedorSur->mueblerias()->attach([7, 8, 9], ['is_primary' => false]);
        $vendedorSur->mueblerias()->updateExistingPivot(7, ['is_primary' => true]);

        // 6. Vendedor Zona Norte
        $vendedorNorte = User::create([
            'name' => 'Luis Hernández',
            'email' => 'luis.hernandez@mueblerias.com',
            'password' => Hash::make('password123'),
            'phone' => '555-2003',
            'address' => 'Zona Industrial, Tijuana',
            'is_active' => true,
        ]);
        $vendedorNorte->syncRoles([$vendedor->id]);
        $vendedorNorte->mueblerias()->attach([4, 5], ['is_primary' => false]);
        $vendedorNorte->mueblerias()->updateExistingPivot(4, ['is_primary' => true]);

        // 7. Usuario Regular 1
        $usuario1 = User::create([
            'name' => 'Pedro Sánchez',
            'email' => 'pedro.sanchez@mueblerias.com',
            'password' => Hash::make('password123'),
            'phone' => '555-3001',
            'address' => 'Col. Juárez, CDMX',
            'is_active' => true,
        ]);
        $usuario1->syncRoles([$usuario->id]);
        $usuario1->mueblerias()->attach([1], ['is_primary' => true]);

        // 8. Usuario Regular 2
        $usuario2 = User::create([
            'name' => 'Laura Torres',
            'email' => 'laura.torres@mueblerias.com',
            'password' => Hash::make('password123'),
            'phone' => '555-3002',
            'address' => 'Col. Roma, CDMX',
            'is_active' => true,
        ]);
        $usuario2->syncRoles([$usuario->id]);
        $usuario2->mueblerias()->attach([2], ['is_primary' => true]);

        // 9. Usuario Regular 3
        $usuario3 = User::create([
            'name' => 'Roberto Flores',
            'email' => 'roberto.flores@mueblerias.com',
            'password' => Hash::make('password123'),
            'phone' => '555-3003',
            'address' => 'Col. Condesa, CDMX',
            'is_active' => true,
        ]);
        $usuario3->syncRoles([$usuario->id]);
        $usuario3->mueblerias()->attach([3], ['is_primary' => true]);

        // 10. Vendedor Inactivo (ejemplo de usuario inactivo)
        $vendedorInactivo = User::create([
            'name' => 'Jorge Vargas',
            'email' => 'jorge.vargas@mueblerias.com',
            'password' => Hash::make('password123'),
            'phone' => '555-2004',
            'address' => 'Zona Centro, Querétaro',
            'is_active' => false,
        ]);
        $vendedorInactivo->syncRoles([$vendedor->id]);
        $vendedorInactivo->mueblerias()->attach([10], ['is_primary' => true]);

        // 11. Administrador Zona Occidente
        $adminOccidente = User::create([
            'name' => 'Patricia Morales',
            'email' => 'patricia.morales@mueblerias.com',
            'password' => Hash::make('password123'),
            'phone' => '555-1003',
            'address' => 'Zona Occidente, Guadalajara',
            'is_active' => true,
        ]);
        $adminOccidente->syncRoles([$administrador->id]);
        $adminOccidente->mueblerias()->attach([10, 11, 12], ['is_primary' => false]);
        $adminOccidente->mueblerias()->updateExistingPivot(10, ['is_primary' => true]);

        // 12. Vendedor Multi-Sucursal
        $vendedorMulti = User::create([
            'name' => 'Fernando Ruiz',
            'email' => 'fernando.ruiz@mueblerias.com',
            'password' => Hash::make('password123'),
            'phone' => '555-2005',
            'address' => 'Zona Metropolitana, CDMX',
            'is_active' => true,
        ]);
        $vendedorMulti->syncRoles([$vendedor->id]);
        $vendedorMulti->mueblerias()->attach([1, 3, 5, 7], ['is_primary' => false]);
        $vendedorMulti->mueblerias()->updateExistingPivot(1, ['is_primary' => true]);

        // 13. Usuario sin mueblería asignada
        $usuarioSinMuebleria = User::create([
            'name' => 'Diana Castro',
            'email' => 'diana.castro@mueblerias.com',
            'password' => Hash::make('password123'),
            'phone' => '555-3004',
            'address' => 'Col. Polanco, CDMX',
            'is_active' => true,
        ]);
        $usuarioSinMuebleria->syncRoles([$usuario->id]);

        // 14. Vendedor Recién Contratado
        $vendedorNuevo = User::create([
            'name' => 'Miguel Ángel López',
            'email' => 'miguel.lopez@mueblerias.com',
            'password' => Hash::make('password123'),
            'phone' => '555-2006',
            'address' => 'Zona Este, Veracruz',
            'is_active' => true,
        ]);
        $vendedorNuevo->syncRoles([$vendedor->id]);
        $vendedorNuevo->mueblerias()->attach([13], ['is_primary' => true]);

        // 15. Administrador Sistema
        $adminSistema = User::create([
            'name' => 'Gabriela Jiménez',
            'email' => 'gabriela.jimenez@mueblerias.com',
            'password' => Hash::make('password123'),
            'phone' => '555-1004',
            'address' => 'Corporativo Central, CDMX',
            'is_active' => true,
        ]);
        $adminSistema->syncRoles([$administrador->id]);
        $adminSistema->mueblerias()->attach([1, 2, 3, 4, 5, 6, 7, 8], ['is_primary' => false]);
        $adminSistema->mueblerias()->updateExistingPivot(1, ['is_primary' => true]);

        $this->command->info('15 usuarios creados exitosamente!');
        $this->command->info('');
        $this->command->info('=== CREDENCIALES DE ACCESO ===');
        $this->command->info('Israel Duran (Administrador):');
        $this->command->info('Email: israel_duran@outlook.com');
        $this->command->info('Password: password123');
        $this->command->info('');
        $this->command->info('Super Admin:');
        $this->command->info('Email: superadmin@mueblerias.com');
        $this->command->info('Password: password123');
        $this->command->info('');
        $this->command->info('NOTA: Cambiar contraseñas en producción!');
    }

    /**
     * Asegurar que existan los roles necesarios
     */
    private function ensureRolesExist(): void
    {
        $roles = [
            ['id' => 1, 'name' => 'Super Admin'],
            ['id' => 2, 'name' => 'Administrador'],
            ['id' => 3, 'name' => 'Vendedor'],
            ['id' => 4, 'name' => 'Usuario'],
        ];

        foreach ($roles as $roleData) {
            Role::firstOrCreate(
                ['id' => $roleData['id']],
                ['name' => $roleData['name'], 'guard_name' => 'web']
            );
        }

        $this->command->info('Roles verificados/creados exitosamente!');
    }
}