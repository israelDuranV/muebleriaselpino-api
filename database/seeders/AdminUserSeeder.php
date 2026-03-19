<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Crear el rol si no existe
        $adminRole = Role::firstOrCreate(['name' => 'administrador']);

        // Crear el usuario
        $user = User::firstOrCreate(
            ['email' => 'israel_duran@outlook.com'],
            [
                'name' => 'israelduran',
                'password' => bcrypt('sanguisetcinis'), // cambia la contraseña
            ]
        );

        // Asignar el rol al usuario
        $user->assignRole($adminRole);
    }
}

